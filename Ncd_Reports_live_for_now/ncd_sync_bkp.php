<?php
/**
 * ============================================================
 *  NCD (National Cooperative Database) — Full Sync Script
 *  Ministry of Cooperation, Govt of India
 * ============================================================
 *  What this file does:
 *   1. Creates ALL NCD tables (prefixed ncd_) if not already there
 *   2. Pulls data from every API endpoint documented in the spec
 *   3. Upserts the rows into the matching table
 *
 *  HOW TO USE
 *  ----------
 *  1. Fill in DB_* and API_* constants below.
 *  2. Run:  php ncd_sync.php
 *     Or run one section:  php ncd_sync.php --only=masters
 *     Sections: masters | geo | cooperatives | sectors | aop
 *  3. Check ncd_sync_log for run history.
 * ============================================================
 */

// ─────────────────────────────────────────────
//  CONFIG  — change these before running
// ─────────────────────────────────────────────
define('DB_HOST',     'localhost');
define('DB_NAME',     'upcdc_2025');
define('DB_USER',     'root');
define('DB_PASS',     'mysql');
define('DB_CHARSET',  'utf8mb4');

define('API_BASE',    'https://api.cooperatives.gov.in');
define('API_KEY',     'YOUR_STATE_API_KEY');   // unique key issued to your state
define('STATE_CODE',  9);                       // e.g. 9 = Uttar Pradesh

// How many rows to fetch per paginated page (Area of Operation - Rural)
define('AOP_PAGE_LIMIT', 100000);
// ─────────────────────────────────────────────

// ── Bootstrap ────────────────────────────────
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(0);          // long-running sync
ini_set('memory_limit', '512M');

$pdo = connectDB();
createAllTables($pdo);

// ── Decide which sections to run ─────────────
$only = null;
foreach ($argv ?? [] as $arg) {
    if (str_starts_with($arg, '--only=')) {
        $only = substr($arg, 7);
    }
}

$sections = ['masters', 'geo', 'cooperatives', 'sectors', 'aop'];
foreach ($sections as $s) {
    if ($only && $only !== $s) continue;
    log_msg("=== Starting section: $s ===");
    call_user_func("sync_$s", $pdo);
}

log_msg("=== NCD Sync complete ===");


// ╔══════════════════════════════════════════════════════════╗
// ║                  DATABASE CONNECTION                     ║
// ╚══════════════════════════════════════════════════════════╝
function connectDB(): PDO {
    $dsn = sprintf(
        'mysql:host=%s;dbname=%s;charset=%s',
        DB_HOST, DB_NAME, DB_CHARSET
    );
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec("SET time_zone = '+05:30'");
    return $pdo;
}


// ╔══════════════════════════════════════════════════════════╗
// ║              CREATE ALL TABLES (IF NOT EXISTS)           ║
// ╚══════════════════════════════════════════════════════════╝
function createAllTables(PDO $pdo): void {
    log_msg("Creating / verifying tables …");

    $ddl = <<<'SQL'

    -- ── Sync log ──────────────────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_sync_log (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        api_name      VARCHAR(120),
        records_saved INT DEFAULT 0,
        ran_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    -- ── 1. States ─────────────────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_states (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        state_code    INT UNIQUE,
        name          VARCHAR(120),
        hindi_name    VARCHAR(255),
        state_or_ut   CHAR(1),
        sr_no         INT,
        updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 2. Districts ──────────────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_districts (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        state_code    INT,
        district_code INT UNIQUE,
        district_name VARCHAR(120),
        updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_state (state_code)
    );

    -- ── 3. Blocks ─────────────────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_blocks (
        id                INT AUTO_INCREMENT PRIMARY KEY,
        state_code        INT,
        district_code     INT,
        block_code        INT UNIQUE,
        block_version     VARCHAR(20),
        name              VARCHAR(150),
        block_name_hindi  VARCHAR(255),
        name_local        VARCHAR(255),
        updated_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_state   (state_code),
        INDEX idx_dist    (district_code)
    );

    -- ── 4. Districts–Blocks–GP–Villages ───────────────────────
    CREATE TABLE IF NOT EXISTS ncd_state_district_block_gp_village (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        state_code                INT,
        state_name                VARCHAR(120),
        district_code             INT,
        district_name             VARCHAR(120),
        block_code                INT,
        block_name                VARCHAR(150),
        gram_panchayat_code       INT,
        gram_panchayat_name       VARCHAR(200),
        gram_panchayat_name_hindi VARCHAR(255),
        village_code              INT UNIQUE,
        village_name              VARCHAR(200),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_state           (state_code),
        INDEX idx_gp              (gram_panchayat_code)
    );

    -- ── 5. Urban Local Bodies ─────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_urban_local_bodies (
        id                      INT AUTO_INCREMENT PRIMARY KEY,
        state_code              INT,
        state_name              VARCHAR(120),
        district_code           INT,
        district_name           VARCHAR(120),
        localbody_type_code     INT,
        localbody_type_name     VARCHAR(120),
        localbody_type_name_hindi VARCHAR(255),
        localbody_code          INT UNIQUE,
        local_body_name         VARCHAR(200),
        local_body_name_hindi   VARCHAR(255),
        updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_state         (state_code)
    );

    -- ── 6. Urban Local Body Wards ─────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_urban_local_body_wards (
        id                    INT AUTO_INCREMENT PRIMARY KEY,
        state_code            INT,
        state_name            VARCHAR(120),
        local_body_code       INT,
        local_body_name       VARCHAR(200),
        local_body_name_hindi VARCHAR(255),
        ward_code             INT UNIQUE,
        ward_name             VARCHAR(200),
        ward_name_hindi       VARCHAR(255),
        updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_state       (state_code),
        INDEX idx_ulb         (local_body_code)
    );

    -- ── 7. Sectors ────────────────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_sectors (
        id         INT UNIQUE,
        name       VARCHAR(200),
        hindi_name VARCHAR(400),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 8. Sub-Sectors ────────────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_sub_sectors (
        id                    INT AUTO_INCREMENT PRIMARY KEY,
        sub_sector_id         INT UNIQUE,
        primary_activities_id INT,
        sub_sector_name       VARCHAR(255),
        sub_sector_name_hindi VARCHAR(400),
        updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9a. Audit Categories ──────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_audit_categories (
        id                    INT UNIQUE,
        name                  VARCHAR(200),
        audit_categories_hindi VARCHAR(400),
        updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9b. Society Implementing Schemes ─────────────────────
    CREATE TABLE IF NOT EXISTS ncd_society_implementing_schemes (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id           VARCHAR(40),
        gov_scheme_name  VARCHAR(500),
        gov_scheme_type  VARCHAR(200),
        total_amount     DECIMAL(17,2),
        st_code          INT,
        dist_code        INT,
        updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_ncd_scheme (ncd_id, gov_scheme_name(100))
    );

    -- ── 9c. Cooperative Registration Lands ───────────────────
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_lands (
        id            INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id        VARCHAR(40) UNIQUE,
        land_owned    DECIMAL(15,3),
        land_leased   DECIMAL(15,3),
        land_allotted DECIMAL(15,3),
        land_total    DECIMAL(15,3),
        st_code       INT,
        dist_code     INT,
        updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9d. Type of Activities Khadi Gram ────────────────────
    CREATE TABLE IF NOT EXISTS ncd_type_of_activities_khadi_gram (
        id                          INT UNIQUE,
        type_of_activities_name     VARCHAR(255),
        type_of_activities_name_hindi VARCHAR(400),
        updated_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9e. Members Details ───────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_members_details (
        id        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id    VARCHAR(40) UNIQUE,
        m_general INT, m_sc INT, m_st INT, m_obc INT,
        f_general INT, f_sc INT, f_st INT, f_obc INT,
        t_general INT, t_sc INT, t_st INT, t_obc INT,
        st_code   INT,
        dist_code INT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9f. Society Audit Years ───────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_society_audit_years (
        id                    INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                VARCHAR(40),
        audit_category        INT,
        audit_year            VARCHAR(10),
        sector_type           INT,
        sector                INT,
        annual_profit         DECIMAL(17,2),
        annual_loss           DECIMAL(17,2),
        state_code            INT,
        district_code         INT,
        share_capital         DECIMAL(17,2),
        reserve_fund          DECIMAL(17,2),
        revenue               DECIMAL(17,2),
        deposit               DECIMAL(17,2),
        loan_and_advance      DECIMAL(17,2),
        borrowings            DECIMAL(17,2),
        total_assets          DECIMAL(17,2),
        no_of_members         INT,
        no_of_branches        INT,
        total_credit_provided DECIMAL(17,2),
        paid_up_share         DECIMAL(17,2),
        annual_turnover       DECIMAL(17,2),
        annual_income         DECIMAL(17,2),
        annual_ucb_expenditr  DECIMAL(17,2),
        asset_ucb             DECIMAL(17,2),
        liability_ucb         DECIMAL(17,2),
        paid_up_members       DECIMAL(17,2),
        paid_up_government_bodies DECIMAL(17,2),
        paid_up_total         DECIMAL(17,2),
        annual_expenses       DECIMAL(17,2),
        updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_ncd_year (ncd_id, audit_year)
    );

    -- ── 9g. Registration Authorities ─────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_registration_authorities (
        id                    INT UNIQUE,
        authority_name        VARCHAR(255),
        authority_name_hindi  VARCHAR(400),
        primary_activity      INT,
        updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9h. Area of Operations (master lookup) ────────────────
    CREATE TABLE IF NOT EXISTS ncd_area_of_operations (
        id          INT UNIQUE,
        urban_rural INT,
        name        VARCHAR(100),
        updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9i. Cooperative Society Banks ────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_cooperative_society_banks (
        id         INT UNIQUE,
        bank_name  VARCHAR(200),
        bank_type  INT,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9j. Designations ─────────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_designations (
        id                  INT UNIQUE,
        name                VARCHAR(200),
        designations_hindi  VARCHAR(400),
        updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9k. Cooperative Society Facilities ───────────────────
    CREATE TABLE IF NOT EXISTS ncd_cooperative_society_facilities (
        id                 INT UNIQUE,
        name               VARCHAR(255),
        primary_activity_id INT,
        updated_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9l. Office Building Types ─────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_office_building_types (
        id                        INT UNIQUE,
        name                      VARCHAR(200),
        office_building_types_hindi VARCHAR(400),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9m. Water Body Types ─────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_water_body_types (
        id                    INT UNIQUE,
        name                  VARCHAR(200),
        water_body_types_hindi VARCHAR(400),
        updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- ── 9n. Board of Directors ────────────────────────────────
    CREATE TABLE IF NOT EXISTS ncd_board_of_directors (
        id                          INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                      VARCHAR(40),
        bod_name                    VARCHAR(255),
        father_name                 VARCHAR(255),
        gender                      TINYINT,
        mobile_number               VARCHAR(15),
        email_id                    VARCHAR(255),
        id_proof_type               VARCHAR(50),
        id_proof_no                 VARCHAR(100),
        bod_designation             INT,
        from_date                   DATE,
        to_date                     DATE,
        is_any_other_cooperative    TINYINT,
        other_cooperative_society_name VARCHAR(255),
        sector_code                 INT,
        st_code                     INT,
        dist_code                   INT,
        updated_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_ncd               (ncd_id)
    );

    -- ── 10. Main Cooperative Registrations ───────────────────
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations (
        id                              INT AUTO_INCREMENT PRIMARY KEY,
        cooperative_society_name        VARCHAR(255),
        local_langauge_society_name     VARCHAR(255),
        registration_authoritie_id      INT,
        reference_year                  INT,
        date_registration               DATE,
        registration_number             VARCHAR(255),
        cooperative_society_type_id     INT,
        area_of_operation_id            INT,
        water_body_type_id              INT,
        sector_of_operation_type        INT,
        sector_of_operation             INT,
        functional_status               INT,
        location_of_head_quarter        INT,
        state_code                      INT,
        district_code                   INT,
        block_code                      INT,
        gram_panchayat_code             INT,
        is_coastal                      TINYINT,
        village_code                    INT,
        urban_local_body_type_code      INT,
        urban_local_body_code           INT,
        locality_ward_code              INT,
        pincode                         INT,
        full_address                    VARCHAR(255),
        contact_person                  VARCHAR(255),
        designation                     INT,
        mobile                          VARCHAR(15),
        landline                        VARCHAR(17),
        email                           VARCHAR(255),
        members_of_society              MEDIUMINT,
        financial_audit                 TINYINT,
        audit_complete_year             INT,
        category_audit                  INT,
        is_profit_making                TINYINT,
        annual_turnover                 DECIMAL(17,2),
        annual_loss                     DECIMAL(17,3),
        is_dividend_paid                TINYINT,
        dividend_rate                   DECIMAL(10,3),
        operation_area_location         INT,
        bank_type                       VARCHAR(10),
        cooperative_society_bank_id     VARCHAR(150),
        other_bank                      VARCHAR(255),
        cooperative_id                  VARCHAR(40) UNIQUE,
        pacs_id                         VARCHAR(40),
        pan_no                          VARCHAR(12),
        gst_no                          VARCHAR(40),
        how_many_branches               INT,
        full_time_secretary             CHAR(3),
        mobile_number_of_secretary      VARCHAR(15),
        alternate_contact_no_for_pacs   VARCHAR(15),
        updated_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_state                 (state_code),
        INDEX idx_sector                (sector_of_operation)
    );

    -- ── Sector Tables ─────────────────────────────────────────

    -- 1. Agriculture & Allied
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_agriculture (
        id                      INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                  VARCHAR(40) UNIQUE,
        st_code                 INT,
        dist_code               INT,
        type_society            VARCHAR(255),
        has_building            TINYINT,
        building_type           INT,
        authorised_share        DOUBLE,
        paid_up_members         DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total           DOUBLE,
        annual_turn_over        DOUBLE,
        individual_member       MEDIUMINT,
        institutional_member    MEDIUMINT,
        has_pool_land           TINYINT,
        has_gov_land            TINYINT,
        member_vested_right     TINYINT,
        is_member_work          TINYINT,
        society_common_pool     TINYINT,
        is_utilize_pool         TINYINT,
        harvesting              TINYINT,
        farming_mech            VARCHAR(100),
        irrigation_means        VARCHAR(100),
        updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 2. Agro Processing / Industrial
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_processing (
        id                      INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                  VARCHAR(40) UNIQUE,
        st_code                 INT,
        dist_code               INT,
        individual_member       MEDIUMINT,
        institutional_member    MEDIUMINT,
        total_member            MEDIUMINT,
        type_society            INT,
        has_building            TINYINT,
        building_type           INT,
        authorised_share        DOUBLE,
        paid_up_members         DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total           DOUBLE,
        annual_turn_over        DOUBLE,
        processing_unit         TINYINT,
        processing_unit_number  BIGINT,
        processing_by_members   TINYINT,
        work_divided            TINYINT,
        product_taken           TINYINT,
        material_available      TINYINT,
        wastes_generated        TINYINT,
        waste_management_facility TINYINT,
        operate_shops           TINYINT,
        operate_shops_number    BIGINT,
        product_sale_out_of_area TINYINT,
        updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 3. Bee Farming
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_bee (
        id                      INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                  VARCHAR(40) UNIQUE,
        st_code                 INT,
        dist_code               INT,
        type_bee                INT,
        has_building            TINYINT,
        building_type           INT,
        authorised_share        DOUBLE,
        paid_up_members         DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total           DOUBLE,
        annual_turn_over        DOUBLE,
        common_yard             TINYINT,
        no_of_behives           BIGINT,
        type_behives            INT,
        rear_by_member          TINYINT,
        guidance_by_member      TINYINT,
        type_product            VARCHAR(100),
        is_bee_plant_grow       TINYINT,
        is_cleaning_process     TINYINT,
        is_waste_facility       TINYINT,
        own_brand_honey         TINYINT,
        is_operate_retail       TINYINT,
        no_of_retail            BIGINT,
        is_product_sale_out     TINYINT,
        facilities              VARCHAR(100),
        updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 4. Consumer Cooperative
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_consumer (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id           VARCHAR(40) UNIQUE,
        st_code          INT,
        dist_code        INT,
        has_building     TINYINT,
        has_store        TINYINT,
        no_of_outlets    INT,
        building_type    INT,
        authorised_share FLOAT,
        paid_up_share    FLOAT,
        annual_turn_over FLOAT,
        facilities       VARCHAR(100),
        updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 5. Credit & Thrift
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_credit_thrift (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        has_building              TINYINT,
        building_type             INT,
        authorised_share          FLOAT,
        paid_up_share             FLOAT,
        total_deposit             FLOAT,
        pack_total_outstanding_loan FLOAT,
        facilities                VARCHAR(100),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 6. Dairy Cooperative
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registration_dairy (
        id                    INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                VARCHAR(40) UNIQUE,
        st_code               INT,
        dist_code             INT,
        milk_collection       INT,
        credit_facility       TINYINT,
        credit_provided       DECIMAL(17,3),
        milk_collection_unit  TINYINT,
        milk_collection_capicity INT,
        transport_milk        TINYINT,
        bulk_milk_unit        TINYINT,
        milk_testing          TINYINT,
        processing            TINYINT,
        other_facility        TEXT,
        is_bank_mitra         TINYINT,
        bank_mitra_details    TEXT,
        is_micro_atm          TINYINT,
        micro_atm_details     TEXT,
        updated_at            TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 7. Educational & Training
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_education (
        id                              INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                          VARCHAR(40) UNIQUE,
        st_code                         INT,
        dist_code                       INT,
        type_society                    INT,
        has_building                    TINYINT,
        building_type                   INT,
        has_land                        TINYINT,
        authorised_share                DOUBLE,
        paid_up_members                 DOUBLE,
        paid_up_government_bodies       DOUBLE,
        paid_up_total                   DOUBLE,
        annual_turn_over                DOUBLE,
        individual_member               BIGINT,
        institutional_member            BIGINT,
        level_of_edu                    INT,
        duration_of_course              INT,
        level_and_duration_of_course    VARCHAR(255),
        course_in_audit                 DOUBLE,
        stu_in_audit                    DOUBLE,
        training_course_in_audit        DOUBLE,
        participants_in_audit           DOUBLE,
        course_international_participant TINYINT,
        no_of_training_course           INT,
        attended_training               INT,
        society_recruit                 TINYINT,
        no_regular_faculty              INT,
        no_other_faculty                INT,
        facilities                      VARCHAR(100),
        updated_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 8/15/21. PACS / FSS / LAMPS  (same table per spec)
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registration_pacs (
        id                              INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                          VARCHAR(40) UNIQUE,
        st_code                         INT,
        dist_code                       INT,
        has_building                    TINYINT,
        building_type                   INT,
        fertilizer_distribution         TINYINT,
        fertilizer_distribution_qty     INT,
        fertilizer_distribution_details TEXT,
        pesticide_distribution          TINYINT,
        pesticide_distribution_qty      INT,
        seed_distribution               TINYINT,
        seed_distribution_qty           INT,
        fair_price                      TINYINT,
        fair_price_qty                  INT,
        fair_price_details              TEXT,
        is_foodgrains                   TINYINT,
        foodgrains_qty                  INT,
        agricultural_implements         TINYINT,
        agricultural_implements_text    TEXT,
        dry_storage                     TINYINT,
        dry_storage_capicity            DECIMAL(10,2),
        cold_storage                    TINYINT,
        cold_storage_capicity           DECIMAL(10,2),
        milk_unit                       TINYINT,
        milk_capicity_unit              VARCHAR(20),
        food_processing                 TINYINT,
        food_processing_type            TEXT,
        other_facility                  TEXT,
        is_socitey_has_land             INT,
        pack_involved_fish_catch        INT,
        pack_annual_fish_catch          DECIMAL(10,3),
        pack_total_outstanding_loan     DOUBLE,
        pack_revenue_non_credit         DECIMAL(17,3),
        is_lgs_program                  TINYINT,
        lgs_capacity                    DECIMAL(17,3),
        is_csc                          TINYINT,
        csc_revenue                     INT,
        csc_details                     TEXT,
        is_fpo                          TINYINT,
        fpo_details                     TEXT,
        is_lpg_distributership          TINYINT,
        lpg_distributership_details     TEXT,
        is_bcp_pump                     TINYINT,
        bcp_pump_details                TEXT,
        is_dpp_diesel                   TINYINT,
        dpp_diesel_details              TEXT,
        is_jak                          TINYINT,
        jak_qty                         INT,
        is_pmksk                        TINYINT,
        pmksk_details                   TEXT,
        is_paani_samity                 TINYINT,
        paani_samity_details            TEXT,
        is_pm_kusum_scheme              TINYINT,
        pm_kusum_scheme_details         TEXT,
        updated_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 9. Fishery Cooperative
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registration_fishery (
        id                   INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id               VARCHAR(40) UNIQUE,
        st_code              INT,
        dist_code            INT,
        annual_fish_catch    DECIMAL(17,3),
        credit_facility      TINYINT,
        total_credit_provided DECIMAL(17,3),
        fuel_distribution    TINYINT,
        marketing            TINYINT,
        cold_storage         TINYINT,
        transportation       TINYINT,
        other_facility       TEXT,
        is_fpo_fisheries     TINYINT,
        fpo_fisheries_details TEXT,
        updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 10. Handicraft
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_handicraft (
        id                       INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                   VARCHAR(40) UNIQUE,
        st_code                  INT,
        dist_code                INT,
        has_building             TINYINT,
        building_type            INT,
        authorised_share         DOUBLE,
        paid_up_members          DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total            DOUBLE,
        individual_member        MEDIUMINT,
        institutional_member     MEDIUMINT,
        annual_turn_over         DOUBLE,
        type_raw                 INT,
        type_produce             INT,
        common_work_place        TINYINT,
        workplace_operate        INT,
        is_work_by_member        TINYINT,
        is_training_provide      TINYINT,
        is_raw_provide           TINYINT,
        is_raw_easy_avail        TINYINT,
        is_waste_generate        TINYINT,
        is_waste_facility        TINYINT,
        is_operate_retail        TINYINT,
        no_of_retail             INT,
        is_product_sale_out      TINYINT,
        facilities               INT,
        updated_at               TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 11. Handloom Textile & Weavers
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_handloom (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        has_building              TINYINT,
        building_type             INT,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        annual_turn_over          DOUBLE,
        individual_member         MEDIUMINT,
        institutional_member      MEDIUMINT,
        power_loom_type           VARCHAR(100),
        hand_loom_type            VARCHAR(100),
        no_of_loom                BIGINT,
        raw_product_taken         TINYINT,
        raw_material_available    TINYINT,
        waste_generate            TINYINT,
        waste_available           TINYINT,
        operate_retail            TINYINT,
        no_of_retail              BIGINT,
        product_sale_out          TINYINT,
        operated_member_themself  TINYINT,
        is_user_work_divide       TINYINT,
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 12. Housing Cooperative
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_housing (
        id                              INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                          VARCHAR(40) UNIQUE,
        st_code                         INT,
        dist_code                       INT,
        type_society                    INT,
        has_building                    TINYINT,
        building_type                   INT,
        has_land                        TINYINT,
        authorised_share                DOUBLE,
        paid_up_members                 DOUBLE,
        paid_up_government_bodies       DOUBLE,
        paid_up_total                   DOUBLE,
        annual_turn_over                DOUBLE,
        annual_expenses                 DOUBLE,
        loan_facilities                 TINYINT,
        number_of_houses_audit_year     INT,
        number_of_houses_during_year    INT,
        number_of_houses_construction   INT,
        facilities                      INT,
        updated_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 13. Jute and Coir
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_jute (
        id                   INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id               VARCHAR(40) UNIQUE,
        st_code              INT,
        dist_code            INT,
        has_building         TINYINT,
        building_type        INT,
        authorised_share     DOUBLE,
        annual_turn_over     DOUBLE,
        type_raw             VARCHAR(100),
        type_produce         VARCHAR(255),
        common_work_place    TINYINT,
        workplace_operate    DOUBLE,
        is_work_by_member    TINYINT,
        is_training_provide  TINYINT,
        is_raw_provide       TINYINT,
        is_raw_easy_avail    TINYINT,
        is_waste_generate    TINYINT,
        is_waste_facility    TINYINT,
        is_operate_retail    TINYINT,
        no_of_retail         BIGINT,
        is_product_sale_out  TINYINT,
        facilities           INT,
        updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 14. Labour Cooperative
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_labour (
        id                              INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                          VARCHAR(40) UNIQUE,
        st_code                         INT,
        dist_code                       INT,
        type_society                    INT,
        has_building                    TINYINT,
        building_type                   INT,
        authorised_share                DOUBLE,
        paid_up_members                 DOUBLE,
        paid_up_government_bodies       DOUBLE,
        paid_up_total                   DOUBLE,
        annual_turn_over                DOUBLE,
        annual_expenses                 DOUBLE,
        work_allot_state_dist_federation TINYINT,
        work_guide_state_dist_federation TINYINT,
        concession_state_gov            TINYINT,
        concession_centre_gov           TINYINT,
        facilities                      INT,
        updated_at                      TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 16. Livestock & Poultry
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_livestock (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        type_society              VARCHAR(100),
        has_building              TINYINT,
        building_type             INT,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        individual_member         DOUBLE,
        institutional_member      DOUBLE,
        annual_turn_over          DOUBLE,
        type_produce              VARCHAR(100),
        common_work_place         TINYINT,
        is_work_by_member         TINYINT,
        is_training_provide       TINYINT,
        is_poultry_feed           TINYINT,
        is_collected_from_member  TINYINT,
        is_waste_facility         TINYINT,
        is_operate_retail         TINYINT,
        no_of_retail              INT,
        is_product_sale_out       TINYINT,
        facilities                VARCHAR(100),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 17. Marketing Cooperative
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_marketing (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        has_building              TINYINT,
        building_type             INT,
        has_land                  TINYINT,
        has_warehouses            TINYINT,
        capacity_warehouses       DOUBLE,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        annual_turn_over          DOUBLE,
        annual_expenses           DOUBLE,
        liecense_to_sell          VARCHAR(40),
        sell_the_item             VARCHAR(40),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 18. Miscellaneous Credit
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_cmiscellaneous (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        has_building              TINYINT,
        building_type             INT,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        annual_turn_over          DOUBLE,
        total_deposit             DOUBLE,
        loan_outstanding          DOUBLE,
        individual_member         MEDIUMINT,
        institutional_member      MEDIUMINT,
        facilities                VARCHAR(100),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 19. Miscellaneous Non-Credit
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_miscellaneous (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        has_building              TINYINT,
        building_type             INT,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        annual_turn_over          DOUBLE,
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 20. Multipurpose
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_multi (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        sec_activity              VARCHAR(100),
        has_building              TINYINT,
        building_type             INT,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        annual_turn_over          DOUBLE,
        has_storage               TINYINT,
        storage_capacity          DOUBLE,
        provide_raw               TINYINT,
        guidance_by_member        TINYINT,
        is_operate_retail         TINYINT,
        no_of_retail              BIGINT,
        is_product_sale_out       TINYINT,
        facilities                VARCHAR(100),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 22. Sericulture
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_sericulture (
        id                          INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                      VARCHAR(40) UNIQUE,
        st_code                     INT,
        dist_code                   INT,
        type_society                VARCHAR(100),
        has_building                TINYINT,
        building_type               INT,
        authorised_share            DOUBLE,
        annual_turn_over            DOUBLE,
        common_work_place           TINYINT,
        no_rear_house               INT,
        is_work_by_member           TINYINT,
        is_training_provide         TINYINT,
        is_rear_appliance           TINYINT,
        is_mulberry_easy_available  TINYINT,
        is_cleaning_facility_cocoon TINYINT,
        is_spinning_weav            TINYINT,
        is_waste_facility           TINYINT,
        is_operate_retail           TINYINT,
        no_of_retail                BIGINT,
        facilities                  VARCHAR(100),
        updated_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 23. Social Welfare & Cultural
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_social (
        id                          INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                      VARCHAR(40) UNIQUE,
        st_code                     INT,
        dist_code                   INT,
        type_society                VARCHAR(100),
        has_building                TINYINT,
        building_type               INT,
        authorised_share            DOUBLE,
        paid_up_members             DOUBLE,
        paid_up_government_bodies   DOUBLE,
        paid_up_total               DOUBLE,
        annual_turn_over            DOUBLE,
        type_social_culture_activity VARCHAR(100),
        has_common                  TINYINT,
        is_operate_by_member        TINYINT,
        guidance_by_member          TINYINT,
        is_operate_vehicle          TINYINT,
        no_of_vehicle               BIGINT,
        facilities                  VARCHAR(100),
        updated_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 24. Sugar Mills
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_sugar (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        has_building              TINYINT,
        building_type             INT,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        suger_mills_no            INT,
        build_up_area             DOUBLE,
        open_land_area            DOUBLE,
        total_area                DOUBLE,
        liecensed_capicity        DOUBLE,
        installed_capicity        DOUBLE,
        crushing_period_start     DATE,
        crushing_period_end       DATE,
        product_produced          VARCHAR(255),
        retail_shops              TINYINT,
        retail_shops_no           INT,
        sugercane_input_provided  TINYINT,
        loan_facility             TINYINT,
        waste_management          TINYINT,
        central_government_benefits TINYINT,
        state_government_benefits TINYINT,
        annual_turn_over          DOUBLE,
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 25. Tourism
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_tourism (
        id                        INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                    VARCHAR(40) UNIQUE,
        st_code                   INT,
        dist_code                 INT,
        type_society              INT,
        has_building              TINYINT,
        building_type             INT,
        authorised_share          DOUBLE,
        paid_up_members           DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total             DOUBLE,
        individual_member         MEDIUMINT,
        institutional_member      MEDIUMINT,
        annual_turn_over          DOUBLE,
        pool_resource             TINYINT,
        any_resource_taken        TINYINT,
        is_right_vested           TINYINT,
        facilities                VARCHAR(100),
        updated_at                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 26. Transport
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_transport (
        id                      INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                  VARCHAR(40) UNIQUE,
        st_code                 INT,
        dist_code               INT,
        type_society            INT,
        has_building            TINYINT,
        building_type           INT,
        authorised_share        DOUBLE,
        paid_up_members         DOUBLE,
        paid_up_government_bodies DOUBLE,
        paid_up_total           DOUBLE,
        annual_turn_over        DOUBLE,
        individual_member       MEDIUMINT,
        institutional_member    MEDIUMINT,
        type_owner              INT,
        bus_type_detail         INT,
        truck_type_detail       INT,
        other_type_detail       INT,
        no_passenger_vehicle    BIGINT,
        no_member_travel        BIGINT,
        no_freight_vehicle      BIGINT,
        quantity_good_transport BIGINT,
        member_themself         TINYINT,
        is_user_transport_facility TINYINT,
        updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 27. Tribal SC/ST
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_tribal (
        id                          INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                      VARCHAR(40) UNIQUE,
        st_code                     INT,
        dist_code                   INT,
        type_society                INT,
        has_building                TINYINT,
        building_type               INT,
        authorised_share            DOUBLE,
        paid_up_members             DOUBLE,
        paid_up_government_bodies   DOUBLE,
        paid_up_total               DOUBLE,
        annual_turn_over            DOUBLE,
        state_district_federation   TINYINT,
        society_provide_raw_material TINYINT,
        facilities                  VARCHAR(100),
        updated_at                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 28. Urban Cooperative Bank (UCB)
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_ucb (
        id                      INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                  VARCHAR(40) UNIQUE,
        st_code                 INT,
        dist_code               INT,
        has_building            TINYINT,
        building_type           INT,
        ucb_branch              DOUBLE,
        has_nafcub              TINYINT,
        authorised_share        DOUBLE,
        annual_turn_over        DOUBLE,
        annual_income           DOUBLE,
        annual_ucb_expenditr    DOUBLE,
        asset_ucb               DOUBLE,
        liability_ucb           DOUBLE,
        total_deposit           DOUBLE,
        loan_outstanding        DOUBLE,
        is_gov_scheme_implemented TINYINT,
        is_computerized         TINYINT,
        no_computer_working     INT,
        have_ifsc               TINYINT,
        have_corebanking        TINYINT,
        have_doorstepservice    TINYINT,
        is_aeps                 TINYINT,
        offer_debitcard         TINYINT,
        have_internetbanking    TINYINT,
        offer_creditcard        TINYINT,
        cibil_membership        TINYINT,
        conducting_gab          TINYINT,
        cgtmsemli_member        TINYINT,
        is_saf_to_cust          TINYINT,
        networth                DOUBLE,
        fswm_comp               TINYINT,
        updated_at              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 29. Women Welfare
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_wocoop (
        id                   INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id               VARCHAR(40) UNIQUE,
        st_code              INT,
        dist_code            INT,
        type_society         INT,
        has_building         TINYINT,
        building_type        INT,
        authorised_share     DOUBLE,
        annual_turn_over     DOUBLE,
        is_raw_material_taken TINYINT,
        facilities           VARCHAR(100),
        updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- 30. Khadi Gramodyog
    CREATE TABLE IF NOT EXISTS ncd_cooperative_registrations_khadi_gram (
        id                                      INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id                                  VARCHAR(40) UNIQUE,
        st_code                                 INT,
        dist_code                               INT,
        has_building                            TINYINT,
        building_type                           INT,
        authorised_share                        DOUBLE,
        paid_up_members                         DOUBLE,
        paid_up_government_bodies               DOUBLE,
        paid_up_total                           DOUBLE,
        annual_turn_over                        DOUBLE,
        individual_member                       MEDIUMINT,
        institutional_member                    MEDIUMINT,
        power_loom_type                         VARCHAR(100),
        hand_loom_type                          VARCHAR(100),
        hand_loom_other_type                    VARCHAR(100),
        no_of_loom                              BIGINT,
        raw_product_taken                       TINYINT,
        raw_material_available                  TINYINT,
        waste_generate                          TINYINT,
        waste_available                         TINYINT,
        operate_retail                          TINYINT,
        no_of_retail                            BIGINT,
        product_sale_out                        TINYINT,
        operated_member_themself                TINYINT,
        is_user_work_divide                     TINYINT,
        type_of_activities_khadi_gram           VARCHAR(120),
        do_you_want_to_enter_type_products_produced TINYINT,
        fswm_comp                               TINYINT,
        updated_at                              TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

    -- Area of Operation – Urban (transactional)
    CREATE TABLE IF NOT EXISTS ncd_area_of_operation_urban (
        id                   INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id               VARCHAR(40),
        area_of_operation_id INT,
        state_code           INT,
        district_code        INT,
        local_body_type_code INT,
        local_body_code      INT,
        locality_ward_code   INT,
        updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_aop_urban (ncd_id, local_body_code, locality_ward_code),
        INDEX idx_ncd (ncd_id)
    );

    -- Area of Operation – Rural (transactional)
    CREATE TABLE IF NOT EXISTS ncd_area_of_operation_rural (
        id                   INT AUTO_INCREMENT PRIMARY KEY,
        ncd_id               VARCHAR(40),
        area_of_operation_id INT,
        state_code           INT,
        district_code        INT,
        block_code           INT,
        panchayat_code       INT,
        village_code         INT,
        gp_village_all       TINYINT,
        updated_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY uk_aop_rural (ncd_id, village_code),
        INDEX idx_ncd (ncd_id)
    );

SQL;

    // Execute each statement separately
    foreach (array_filter(array_map('trim', explode(';', $ddl))) as $sql) {
        if ($sql) {
            $pdo->exec($sql);
        }
    }
    log_msg("All tables verified.");
}


// ╔══════════════════════════════════════════════════════════╗
// ║                  SYNC FUNCTIONS                          ║
// ╚══════════════════════════════════════════════════════════╝

// ── Section: Masters (lookup/reference tables) ────────────
function sync_masters(PDO $pdo): void {

    // 1. State
    $data = api_get('/Api/findStateLgCode', ['state' => STATE_CODE]);
    if (!empty($data['message'])) {
        foreach ((array)$data['message'] as $row) {
            upsert($pdo, 'ncd_states', ['state_code' => (int)$row['state_code']], [
                'name'        => $row['name']        ?? null,
                'hindi_name'  => $row['hindi_name']  ?? null,
                'state_or_ut' => $row['state_or_ut'] ?? null,
                'sr_no'       => $row['sr_no']        ?? null,
            ]);
        }
        log_saved($pdo, 'States', count((array)$data['message']));
    }

    // 7. Sectors
    $data = api_get('/en/Api/findNcdSectorCode', []);
    if (!empty($data['message'])) {
        foreach ((array)$data['message'] as $row) {
            upsert($pdo, 'ncd_sectors', ['id' => $row['id']], [
                'name'       => $row['name']       ?? null,
                'hindi_name' => $row['hindi_name'] ?? null,
            ]);
        }
        log_saved($pdo, 'Sectors', count((array)$data['message']));
    }

    // 8. Sub-Sectors  (level=15)
    sync_misc_level($pdo, 15, 'ncd_sub_sectors', 'Sub Sector Details', function(array $r): array {
        return [
            'sub_sector_id'         => $r['sub_sector_id'],
            'primary_activities_id' => $r['primary_activities_id'] ?? null,
            'sub_sector_name'       => $r['sub_sector_name']       ?? null,
            'sub_sector_name_hindi' => $r['sub_sector_name_hindi'] ?? null,
        ];
    }, 'sub_sector_id');

    // 9 – various misc levels
    $miscMap = [
        8  => ['ncd_audit_categories',              'Society Audit Years Details',    'id',
                fn($r) => ['id' => $r['id'], 'name' => $r['name'] ?? null, 'audit_categories_hindi' => $r['audit_categories_hindi'] ?? null]],

        5  => ['ncd_type_of_activities_khadi_gram', 'Type Of Activities Khadi Gram Details', 'id',
                fn($r) => ['id' => $r['id'], 'type_of_activities_name' => $r['type_of_activities_name'] ?? null, 'type_of_activities_name_hindi' => $r['type_of_activities_name_hindi'] ?? null]],

        9  => ['ncd_registration_authorities',      'Registration Authorities Details','id',
                fn($r) => ['id' => $r['id'], 'authority_name' => $r['authority_name'] ?? null, 'authority_name_hindi' => $r['authority_name_hindi'] ?? null, 'primary_activity' => $r['primary_activity'] ?? null]],

        10 => ['ncd_area_of_operations',            'Area Of Operations Details',      'id',
                fn($r) => ['id' => $r['id'], 'urban_rural' => $r['urban_rural'] ?? null, 'name' => $r['name'] ?? null]],

        11 => ['ncd_cooperative_society_banks',     'Area Of Operations Details',      'id',
                fn($r) => ['id' => $r['id'], 'bank_name' => $r['bank_name'] ?? null, 'bank_type' => $r['bank_type'] ?? null]],

        12 => ['ncd_designations',                  'Designations Details',            'id',
                fn($r) => ['id' => $r['id'], 'name' => $r['name'] ?? null, 'designations_hindi' => $r['designations_hindi'] ?? null]],

        13 => ['ncd_cooperative_society_facilities','Cooperative Society Facilities Details','id',
                fn($r) => ['id' => $r['id'], 'name' => $r['name'] ?? null, 'primary_activity_id' => $r['primary_activity_id'] ?? null]],

        16 => ['ncd_office_building_types',         'Sub Sector Details',              'id',
                fn($r) => ['id' => $r['id'], 'name' => $r['name'] ?? null, 'office_building_types_hindi' => $r['office_building_types_hindi'] ?? null]],

        17 => ['ncd_water_body_types',              'Sub Sector Details',              'id',
                fn($r) => ['id' => $r['id'], 'name' => $r['name'] ?? null, 'water_body_types_hindi' => $r['water_body_types_hindi'] ?? null]],
    ];

    foreach ($miscMap as $level => [$table, $resultKey, $uk, $mapper]) {
        sync_misc_level($pdo, $level, $table, $resultKey, $mapper, $uk);
    }

    // Level 3 – Society Implementing Schemes (ncd_id key, not int id)
    sync_misc_level($pdo, 3, 'ncd_society_implementing_schemes',
        'Society Implementing Schemes Details',
        fn($r) => [
            'ncd_id'          => $r['ncd_id'],
            'gov_scheme_name' => $r['gov_scheme_name'] ?? null,
            'gov_scheme_type' => $r['gov_scheme_type'] ?? null,
            'total_amount'    => $r['total_amount']    ?? null,
            'st_code'         => $r['st_code']         ?? null,
            'dist_code'       => $r['dist_code']       ?? null,
        ], null   // no single unique key – bulk insert
    );

    // Level 4 – Coop Registration Lands
    sync_misc_level($pdo, 4, 'ncd_cooperative_registrations_lands',
        'Cooperative Registrations Lands Details',
        fn($r) => [
            'ncd_id'        => $r['ncd_id'],
            'land_owned'    => $r['land_owned']    ?? null,
            'land_leased'   => $r['land_leased']   ?? null,
            'land_allotted' => $r['land_allotted'] ?? null,
            'land_total'    => $r['land_total']    ?? null,
            'st_code'       => $r['st_code']       ?? null,
            'dist_code'     => $r['dist_code']     ?? null,
        ], 'ncd_id'
    );

    // Level 6 – Members Details
    sync_misc_level($pdo, 6, 'ncd_members_details',
        'Members Details',
        fn($r) => [
            'ncd_id'    => $r['ncd_id'],
            'm_general' => $r['m_general'] ?? 0, 'm_sc' => $r['m_sc'] ?? 0,
            'm_st'      => $r['m_st'] ?? 0,      'm_obc' => $r['m_obc'] ?? 0,
            'f_general' => $r['f_general'] ?? 0, 'f_sc' => $r['f_sc'] ?? 0,
            'f_st'      => $r['f_st'] ?? 0,      'f_obc' => $r['f_obc'] ?? 0,
            't_general' => $r['t_general'] ?? 0, 't_sc' => $r['t_sc'] ?? 0,
            't_st'      => $r['t_st'] ?? 0,      't_obc' => $r['t_obc'] ?? 0,
            'st_code'   => $r['st_code']   ?? null,
            'dist_code' => $r['dist_code'] ?? null,
        ], 'ncd_id'
    );

    // Level 7 – Society Audit Years
    sync_misc_level($pdo, 7, 'ncd_society_audit_years',
        'Society Audit Years Details',
        fn($r) => [
            'ncd_id'                  => $r['ncd_id'],
            'audit_category'          => $r['audit_category']   ?? null,
            'audit_year'              => $r['audit_year']        ?? null,
            'sector_type'             => $r['sector_type']       ?? null,
            'sector'                  => $r['sector']            ?? null,
            'annual_profit'           => nullNum($r['annual_profit']  ?? null),
            'annual_loss'             => nullNum($r['annual_loss']    ?? null),
            'state_code'              => $r['state_code']        ?? null,
            'district_code'           => $r['district_code']     ?? null,
            'share_capital'           => nullNum($r['share_capital']  ?? null),
            'reserve_fund'            => nullNum($r['reserve_fund']   ?? null),
            'revenue'                 => nullNum($r['revenue']        ?? null),
            'deposit'                 => nullNum($r['deposit']        ?? null),
            'loan_and_advance'        => nullNum($r['loan_and_advance']    ?? null),
            'borrowings'              => nullNum($r['borrowings']      ?? null),
            'total_assets'            => nullNum($r['total_assets']    ?? null),
            'no_of_members'           => $r['no_of_members']     ?? null,
            'no_of_branches'          => $r['no_of_branches']    ?? null,
            'total_credit_provided'   => nullNum($r['total_credit_provided'] ?? null),
            'paid_up_share'           => nullNum($r['paid_up_share'] ?? null),
            'annual_turnover'         => nullNum($r['annual_turnover'] ?? null),
            'annual_income'           => nullNum($r['annual_income'] ?? null),
            'annual_ucb_expenditr'    => nullNum($r['annual_ucb_expenditr'] ?? null),
            'asset_ucb'               => nullNum($r['asset_ucb'] ?? null),
            'liability_ucb'           => nullNum($r['liability_ucb'] ?? null),
            'paid_up_members'         => nullNum($r['paid_up_members'] ?? null),
            'paid_up_government_bodies' => nullNum($r['paid_up_government_bodies'] ?? null),
            'paid_up_total'           => nullNum($r['paid_up_total'] ?? null),
            'annual_expenses'         => nullNum($r['annual_expenses'] ?? null),
        ], null   // UNIQUE KEY uk_ncd_year handles dedup
    );

    // Level 18 – Board of Directors
    sync_misc_level($pdo, 18, 'ncd_board_of_directors',
        'Board Of Director Details',
        fn($r) => [
            'ncd_id'                         => $r['ncd_id'] ?? null,
            'bod_name'                       => $r['bod_name'] ?? null,
            'father_name'                    => $r['father_name'] ?? null,
            'gender'                         => $r['gender'] ?? null,
            'mobile_number'                  => (string)($r['mobile_number'] ?? ''),
            'email_id'                       => $r['email_id'] ?? null,
            'id_proof_type'                  => $r['id_proof_type'] ?? null,
            'id_proof_no'                    => $r['id_proof_no'] ?? null,
            'bod_designation'                => $r['bod_designation'] ?? null,
            'from_date'                      => dateOrNull($r['from_date'] ?? null),
            'to_date'                        => dateOrNull($r['to_date'] ?? null),
            'is_any_other_cooperative'       => $r['is_any_other_cooperative'] ?? null,
            'other_cooperative_society_name' => $r['other_cooperative_society_name'] ?? null,
            'sector_code'                    => $r['sector_code'] ?? null,
            'st_code'                        => $r['st_code'] ?? null,
            'dist_code'                      => $r['dist_code'] ?? null,
        ], null  // bulk insert, no single unique key
    );
}

// ── Section: Geography ────────────────────────────────────
function sync_geo(PDO $pdo): void {

    // 2. Districts
    $data = api_get('/Api/districtdetailsbystate', ['state' => STATE_CODE]);
    if (!empty($data['result'])) {
        foreach ($data['result'] as $row) {
            upsert($pdo, 'ncd_districts', ['district_code' => $row['district_code']], [
                'state_code'    => STATE_CODE,
                'district_name' => $row['district_name'] ?? null,
            ]);
        }
        log_saved($pdo, 'Districts', count($data['result']));
    }

    // 3. Blocks
    $data = api_get('/en/Api/apiforblocksdata', ['state' => STATE_CODE]);
    if (!empty($data['result'])) {
        $n = 0;
        foreach ($data['result'] as $item) {
            $row = $item['Blocks  Details'] ?? $item;
            upsert($pdo, 'ncd_blocks', ['block_code' => $row['block_code']], [
                'state_code'       => $row['state_code']       ?? STATE_CODE,
                'district_code'    => $row['district_code']    ?? null,
                'block_version'    => $row['block_version']    ?? null,
                'name'             => $row['name']             ?? null,
                'block_name_hindi' => $row['block_name_hindi'] ?? null,
                'name_local'       => $row['name_local']       ?? null,
            ]);
            $n++;
        }
        log_saved($pdo, 'Blocks', $n);
    }

    // 4. Districts–Blocks–GP–Villages
    $data = api_get('/en/Api/apifordistrictsblocksgpvillagesdata', ['state' => STATE_CODE]);
    if (!empty($data['result'])) {
        $n = 0;
        $stmt = $pdo->prepare("
            INSERT INTO ncd_state_district_block_gp_village
                (state_code,state_name,district_code,district_name,block_code,block_name,
                 gram_panchayat_code,gram_panchayat_name,gram_panchayat_name_hindi,village_code,village_name)
            VALUES
                (:state_code,:state_name,:district_code,:district_name,:block_code,:block_name,
                 :gram_panchayat_code,:gram_panchayat_name,:gram_panchayat_name_hindi,:village_code,:village_name)
            ON DUPLICATE KEY UPDATE
                state_name=VALUES(state_name), district_name=VALUES(district_name),
                block_name=VALUES(block_name), gram_panchayat_name=VALUES(gram_panchayat_name),
                village_name=VALUES(village_name)
        ");
        foreach ($data['result'] as $item) {
            $r = $item['Districts - Blocks - GP - Villages Details'] ?? $item;
            $stmt->execute([
                ':state_code'               => $r['state_code']               ?? STATE_CODE,
                ':state_name'               => $r['state_name']               ?? null,
                ':district_code'            => $r['district_code']            ?? null,
                ':district_name'            => $r['district_name']            ?? null,
                ':block_code'               => $r['block_code']               ?? null,
                ':block_name'               => $r['block_name']               ?? null,
                ':gram_panchayat_code'      => $r['gram_panchayat_code']      ?? null,
                ':gram_panchayat_name'      => $r['gram_panchayat_name']      ?? null,
                ':gram_panchayat_name_hindi'=> $r['gram_panchayat_name_hindi']?? null,
                ':village_code'             => $r['village_code']             ?? null,
                ':village_name'             => $r['village_name']             ?? null,
            ]);
            $n++;
        }
        log_saved($pdo, 'GP-Villages', $n);
    }

    // 5. Urban Local Bodies
    $data = api_get('/en/Api/apiforulbdata', ['state' => STATE_CODE]);
    if (!empty($data['result'])) {
        $n = 0;
        foreach ($data['result'] as $item) {
            $r = $item['Urban Local Bodies Details'] ?? $item;
            upsert($pdo, 'ncd_urban_local_bodies', ['localbody_code' => $r['localbody_code']], [
                'state_code'              => $r['state_code']              ?? STATE_CODE,
                'state_name'              => $r['state_name']              ?? null,
                'district_code'           => $r['district_code']           ?? null,
                'district_name'           => $r['district_name']           ?? null,
                'localbody_type_code'     => $r['localbody_type_code']     ?? null,
                'localbody_type_name'     => $r['localbody_type_name']     ?? null,
                'localbody_type_name_hindi'=> $r['localbody_type_name_hindi']?? null,
                'local_body_name'         => $r['local_body_name']         ?? null,
                'local_body_name_hindi'   => $r['local_body_name_hindi']   ?? null,
            ]);
            $n++;
        }
        log_saved($pdo, 'Urban Local Bodies', $n);
    }

    // 6. ULB Wards
    $data = api_get('/en/Api/apiforulbwarddata', ['state' => STATE_CODE]);
    if (!empty($data['result'])) {
        $n = 0;
        foreach ($data['result'] as $item) {
            $r = $item['Urban Local Bodies Details'] ?? $item;
            upsert($pdo, 'ncd_urban_local_body_wards', ['ward_code' => $r['ward_code']], [
                'state_code'            => $r['state_code']            ?? STATE_CODE,
                'state_name'            => $r['state_name']            ?? null,
                'local_body_code'       => $r['local_body_code']       ?? null,
                'local_body_name'       => $r['local_body_name']       ?? null,
                'local_body_name_hindi' => $r['local_body_name_hindi'] ?? null,
                'ward_name'             => $r['ward_name']             ?? null,
                'ward_name_hindi'       => $r['ward_name_name_hindi']  ?? null,
            ]);
            $n++;
        }
        log_saved($pdo, 'ULB Wards', $n);
    }
}

// ── Section: Cooperative Registrations (main table) ───────
function sync_cooperatives(PDO $pdo): void {
    $data = api_get('/MasterApi/stateWiseBasicDetails', ['state' => STATE_CODE]);
    if (empty($data['result'])) {
        log_msg("No cooperative data returned.");
        return;
    }

    $n = 0;
    foreach ($data['result'] as $r) {
        upsert($pdo, 'ncd_cooperative_registrations', ['cooperative_id' => $r['cooperative_id']], [
            'cooperative_society_name'      => $r['cooperative_society_name']      ?? null,
            'local_langauge_society_name'   => $r['local_langauge_society_name']   ?? null,
            'registration_authoritie_id'    => $r['registration_authoritie_id']    ?? null,
            'reference_year'                => $r['reference_year']                ?? null,
            'date_registration'             => dateOrNull($r['date_registration']  ?? null),
            'registration_number'           => $r['registration_number']           ?? null,
            'cooperative_society_type_id'   => $r['cooperative_society_type_id']   ?? null,
            'area_of_operation_id'          => $r['area_of_operation_id']          ?? null,
            'water_body_type_id'            => $r['water_body_type_id']            ?? null,
            'sector_of_operation_type'      => $r['sector_of_operation_type']      ?? null,
            'sector_of_operation'           => $r['sector_of_operation']           ?? null,
            'functional_status'             => $r['functional_status']             ?? null,
            'location_of_head_quarter'      => $r['location_of_head_quarter']      ?? null,
            'state_code'                    => $r['state_code']                    ?? null,
            'district_code'                 => $r['district_code']                 ?? null,
            'block_code'                    => $r['block_code']                    ?? null,
            'gram_panchayat_code'           => $r['gram_panchayat_code']           ?? null,
            'is_coastal'                    => $r['is_coastal']                    ?? null,
            'village_code'                  => $r['village_code']                  ?? null,
            'urban_local_body_type_code'    => nvl($r['urban_local_body_type_code']?? null),
            'urban_local_body_code'         => nvl($r['urban_local_body_code']     ?? null),
            'locality_ward_code'            => nvl($r['locality_ward_code']        ?? null),
            'pincode'                       => $r['pincode']                       ?? null,
            'full_address'                  => $r['full_address']                  ?? null,
            'contact_person'                => $r['contact_person']                ?? null,
            'designation'                   => $r['designation']                   ?? null,
            'mobile'                        => (string)($r['mobile']               ?? ''),
            'landline'                      => $r['landline']                      ?? null,
            'email'                         => $r['email']                         ?? null,
            'members_of_society'            => nvl($r['members_of_society']        ?? null),
            'financial_audit'               => $r['financial_audit']               ?? null,
            'audit_complete_year'           => nvl($r['audit_complete_year']       ?? null),
            'category_audit'                => $r['category_audit']                ?? null,
            'is_profit_making'              => $r['is_profit_making']              ?? null,
            'annual_turnover'               => nullNum($r['annual_turnover']       ?? null),
            'annual_loss'                   => nullNum($r['annual_loss']           ?? null),
            'is_dividend_paid'              => $r['is_dividend_paid']              ?? null,
            'dividend_rate'                 => nullNum($r['dividend_rate']         ?? null),
            'operation_area_location'       => $r['operation_area_location']       ?? null,
            'bank_type'                     => $r['bank_type']                     ?? null,
            'cooperative_society_bank_id'   => $r['cooperative_society_bank_id']   ?? null,
            'other_bank'                    => $r['other_bank']                    ?? null,
            'pacs_id'                       => $r['pacs_id']                       ?? null,
            'pan_no'                        => $r['pan_no']                        ?? null,
            'gst_no'                        => $r['gst_no']                        ?? null,
            'how_many_branches'             => $r['how_many_branches']             ?? null,
            'full_time_secretary'           => $r['full_time_secretary']           ?? null,
            'mobile_number_of_secretary'    => (string)($r['mobile_number_of_secretary'] ?? ''),
            'alternate_contact_no_for_pacs' => $r['alternate_contact_no_for_pacs'] ?? null,
        ]);
        $n++;
    }
    log_saved($pdo, 'Cooperative Registrations', $n);
}

// ── Section: Sector child tables (raw API) ────────────────
/**
 * Sector codes as per the spec  →  [sectorCode => [tableName, dataKey, mapper]]
 */
function sync_sectors(PDO $pdo): void {

    $sectorMap = getSectorMap();

    foreach ($sectorMap as $sectorCode => [$table, $dataKey, $mapper]) {
        $data = api_get('/en/Api/sectorchildtablerawdatastatewise', [
            'state'  => STATE_CODE,
            'sector' => $sectorCode,
        ]);

        if (empty($data['result'])) {
            log_msg("Sector $sectorCode ($table): no data.");
            continue;
        }

        $n = 0;
        foreach ($data['result'] as $item) {
            $r = $item[$dataKey] ?? $item;
            if (empty($r['ncd_id'])) continue;
            $row = $mapper($r);
            $row['ncd_id']    = $r['ncd_id'];
            $row['st_code']   = $r['st_code']   ?? STATE_CODE;
            $row['dist_code'] = $r['dist_code'] ?? null;
            upsert($pdo, $table, ['ncd_id' => $row['ncd_id']], $row);
            $n++;
        }
        log_saved($pdo, "Sector $sectorCode – $table", $n);
    }
}

// ── Section: Area of Operation ────────────────────────────
function sync_aop(PDO $pdo): void {

    // Urban  (level=2)
    $data = api_get('/en/Api/apimiscellanousdata', ['state' => STATE_CODE, 'level' => 2]);
    $n = 0;
    if (!empty($data['result'])) {
        $stmt = $pdo->prepare("
            INSERT INTO ncd_area_of_operation_urban
                (ncd_id,area_of_operation_id,state_code,district_code,local_body_type_code,local_body_code,locality_ward_code)
            VALUES (:ncd_id,:aop_id,:state_code,:district_code,:lb_type,:lb_code,:ward_code)
            ON DUPLICATE KEY UPDATE
                area_of_operation_id=VALUES(area_of_operation_id),
                local_body_type_code=VALUES(local_body_type_code),
                locality_ward_code=VALUES(locality_ward_code)
        ");
        foreach ($data['result'] as $item) {
            $r = $item['Area of Operation Urban Details'] ?? $item;
            $stmt->execute([
                ':ncd_id'      => $r['ncd_id'],
                ':aop_id'      => $r['area_of_operation_id'] ?? null,
                ':state_code'  => $r['state_code']           ?? STATE_CODE,
                ':district_code'=> $r['district_code']       ?? null,
                ':lb_type'     => $r['local_body_type_code'] ?? null,
                ':lb_code'     => $r['local_body_code']      ?? null,
                ':ward_code'   => $r['locality_ward_code']   ?? null,
            ]);
            $n++;
        }
    }
    log_saved($pdo, 'AOP Urban', $n);

    // Rural  (level=1, paginated)
    $page = 1;
    $totalSaved = 0;
    $stmt = $pdo->prepare("
        INSERT INTO ncd_area_of_operation_rural
            (ncd_id,area_of_operation_id,state_code,district_code,block_code,panchayat_code,village_code,gp_village_all)
        VALUES (:ncd_id,:aop_id,:state_code,:district_code,:block_code,:panchayat_code,:village_code,:gp_village_all)
        ON DUPLICATE KEY UPDATE
            area_of_operation_id=VALUES(area_of_operation_id),
            block_code=VALUES(block_code),
            panchayat_code=VALUES(panchayat_code),
            gp_village_all=VALUES(gp_village_all)
    ");

    do {
        $data = api_get('/en/Api/apimiscellanousdata', [
            'state' => STATE_CODE, 'level' => 1, 'page' => $page
        ]);
        if (empty($data['result'])) break;

        foreach ($data['result'] as $item) {
            $r = $item['Area of Operation Rural Details'] ?? $item;
            $stmt->execute([
                ':ncd_id'        => $r['ncd_id'],
                ':aop_id'        => $r['area_of_operation_id'] ?? null,
                ':state_code'    => $r['state_code']           ?? STATE_CODE,
                ':district_code' => $r['district_code']        ?? null,
                ':block_code'    => $r['block_code']           ?? null,
                ':panchayat_code'=> $r['panchayat_code']       ?? null,
                ':village_code'  => $r['village_code']         ?? null,
                ':gp_village_all'=> $r['gp_village_all']       ?? null,
            ]);
            $totalSaved++;
        }

        $totalPages = $data['pagination']['total_pages'] ?? 1;
        log_msg("  AOP Rural page $page / $totalPages");
        $page++;
    } while ($page <= $totalPages);

    log_saved($pdo, 'AOP Rural', $totalSaved);
}


// ╔══════════════════════════════════════════════════════════╗
// ║              SECTOR MAP (code → config)                  ║
// ╚══════════════════════════════════════════════════════════╝
function getSectorMap(): array {
    return [
        // sectorCode => [table, resultKey, mapper]
        77 => ['ncd_cooperative_registrations_agriculture', 'Agriculture Details', fn($r) => [
            'type_society'             => $r['type_society']           ?? null,
            'has_building'             => $r['has_building']           ?? null,
            'building_type'            => $r['building_type']          ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'individual_member'        => $r['individual_member']      ?? null,
            'institutional_member'     => $r['institutional_member']   ?? null,
            'has_pool_land'            => $r['has_pool_land']          ?? null,
            'has_gov_land'             => $r['has_gov_land']           ?? null,
            'member_vested_right'      => $r['member_vested_right']    ?? null,
            'is_member_work'           => $r['is_member_work']         ?? null,
            'society_common_pool'      => $r['society_common_pool']    ?? null,
            'is_utilize_pool'          => $r['is_utilize_pool']        ?? null,
            'harvesting'               => $r['harvesting']             ?? null,
            'farming_mech'             => $r['farming_mech']           ?? null,
            'irrigation_means'         => $r['irrigation_means']       ?? null,
        ]],

        2 => ['ncd_cooperative_registrations_processing', 'Processing Details', fn($r) => [
            'individual_member'        => $r['individual_member']      ?? null,
            'institutional_member'     => $r['institutional_member']   ?? null,
            'total_member'             => $r['total_member'] ?? ($r['total_members'] ?? null),
            'type_society'             => $r['type_society']           ?? null,
            'has_building'             => $r['has_building']           ?? null,
            'building_type'            => $r['building_type']          ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'processing_unit'          => $r['processing_unit']         ?? null,
            'processing_unit_number'   => $r['processing_unit_number']  ?? null,
            'processing_by_members'    => $r['processing_by_members']   ?? null,
            'work_divided'             => $r['work_divided']            ?? null,
            'product_taken'            => $r['product_taken']           ?? null,
            'material_available'       => $r['material_available']      ?? null,
            'wastes_generated'         => $r['wastes_generated']        ?? null,
            'waste_management_facility'=> $r['waste_management_facility']?? null,
            'operate_shops'            => $r['operate_shops']           ?? null,
            'operate_shops_number'     => $r['operate_shops_number']    ?? null,
            'product_sale_out_of_area' => $r['product_sale_out_of_area']?? null,
        ]],

        3 => ['ncd_cooperative_registrations_bee', 'Bee Farming Details', fn($r) => [
            'type_bee'              => $r['type_bee']           ?? null,
            'has_building'          => $r['has_building']       ?? null,
            'building_type'         => $r['building_type']      ?? null,
            'authorised_share'      => nullNum($r['authorised_share']  ?? null),
            'paid_up_members'       => nullNum($r['paid_up_members']   ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies'] ?? null),
            'paid_up_total'         => nullNum($r['paid_up_total']     ?? null),
            'annual_turn_over'      => nullNum($r['annual_turn_over']  ?? null),
            'common_yard'           => $r['common_yard']        ?? null,
            'no_of_behives'         => $r['no_of_behives']      ?? null,
            'type_behives'          => $r['type_behives']       ?? null,
            'rear_by_member'        => $r['rear_by_member']     ?? null,
            'guidance_by_member'    => $r['guidance_by_member'] ?? null,
            'type_product'          => $r['type_product']       ?? null,
            'is_bee_plant_grow'     => $r['is_bee_plant_grow']  ?? null,
            'is_cleaning_process'   => $r['is_cleaning_process']?? null,
            'is_waste_facility'     => $r['is_waste_facility']  ?? null,
            'own_brand_honey'       => $r['own_brand_honey']    ?? null,
            'is_operate_retail'     => $r['is_operate_retail']  ?? null,
            'no_of_retail'          => $r['no_of_retail']       ?? null,
            'is_product_sale_out'   => $r['is_product_sale_out']?? null,
            'facilities'            => $r['facilities']         ?? null,
        ]],

        4 => ['ncd_cooperative_registrations_consumer', 'Consumer Details', fn($r) => [
            'has_building'    => $r['has_building']    ?? null,
            'has_store'       => $r['has_store']       ?? null,
            'no_of_outlets'   => $r['no_of_outlets']   ?? null,
            'building_type'   => $r['building_type']   ?? null,
            'authorised_share'=> nullNum($r['authorised_share'] ?? null),
            'paid_up_share'   => nullNum($r['paid_up_share']   ?? null),
            'annual_turn_over'=> nullNum($r['annual_turn_over']?? null),
            'facilities'      => $r['facilities']      ?? null,
        ]],

        5 => ['ncd_cooperative_registrations_credit_thrift', 'Credit Thrift Details', fn($r) => [
            'has_building'              => $r['has_building']              ?? null,
            'building_type'             => $r['building_type']             ?? null,
            'authorised_share'          => nullNum($r['authorised_share']          ?? null),
            'paid_up_share'             => nullNum($r['paid_up_share']             ?? null),
            'total_deposit'             => nullNum($r['total_deposit']             ?? null),
            'pack_total_outstanding_loan'=> nullNum($r['pack_total_outstanding_loan']?? null),
            'facilities'                => $r['facilities']                ?? null,
        ]],

        6 => ['ncd_cooperative_registration_dairy', 'Dairy Details', fn($r) => [
            'milk_collection'        => $r['milk_collection']       ?? null,
            'credit_facility'        => $r['credit_facility']       ?? null,
            'credit_provided'        => nullNum($r['credit_provided']?? null),
            'milk_collection_unit'   => $r['milk_collection_unit']  ?? null,
            'milk_collection_capicity'=> $r['milk_collection_capicity']?? null,
            'transport_milk'         => $r['transport_milk']        ?? null,
            'bulk_milk_unit'         => $r['bulk_milk_unit']        ?? null,
            'milk_testing'           => $r['milk_testing']          ?? null,
            'processing'             => $r['processing']            ?? null,
            'other_facility'         => $r['other_facility']        ?? null,
            'is_bank_mitra'          => $r['is_bank_mitra']         ?? null,
            'bank_mitra_details'     => $r['bank_mitra_details']    ?? null,
            'is_micro_atm'           => $r['is_micro_atm']          ?? null,
            'micro_atm_details'      => $r['micro_atm_details']     ?? null,
        ]],

        7 => ['ncd_cooperative_registrations_education', 'Education Details', fn($r) => [
            'type_society'                     => $r['type_society']                    ?? null,
            'has_building'                     => $r['has_building']                    ?? null,
            'building_type'                    => $r['building_type']                   ?? null,
            'has_land'                         => $r['has_land']                        ?? null,
            'authorised_share'                 => nullNum($r['authorised_share']                 ?? null),
            'paid_up_members'                  => nullNum($r['paid_up_members']                  ?? null),
            'paid_up_government_bodies'        => nullNum($r['paid_up_government_bodies']        ?? null),
            'paid_up_total'                    => nullNum($r['paid_up_total']                    ?? null),
            'annual_turn_over'                 => nullNum($r['annual_turn_over']                 ?? null),
            'individual_member'                => $r['individual_member']               ?? null,
            'institutional_member'             => $r['institutional_member']            ?? null,
            'level_of_edu'                     => $r['level_of_edu']                   ?? null,
            'duration_of_course'               => $r['duration_of_course']             ?? null,
            'level_and_duration_of_course'     => $r['level_and_duration_of_course']   ?? null,
            'course_in_audit'                  => nullNum($r['course_in_audit']                  ?? null),
            'stu_in_audit'                     => nullNum($r['stu_in_audit']                     ?? null),
            'training_course_in_audit'         => nullNum($r['training_course_in_audit']         ?? null),
            'participants_in_audit'            => nullNum($r['participants_in_audit']            ?? null),
            'course_international_participant' => $r['course_international_participant']?? null,
            'no_of_training_course'            => $r['no_of_training_course']          ?? null,
            'attended_training'                => $r['attended_training']              ?? null,
            'society_recruit'                  => $r['society_recruit']                ?? null,
            'no_regular_faculty'               => $r['no_regular_faculty']             ?? null,
            'no_other_faculty'                 => $r['no_other_faculty']               ?? null,
            'facilities'                       => $r['facilities']                     ?? null,
        ]],

        // 8=FSS, 15=LAMPS, 21=PACS → all go to same table
        8  => ['ncd_cooperative_registration_pacs', 'FSS Details',  'pacsMapper'],
        15 => ['ncd_cooperative_registration_pacs', 'LAMPS Details','pacsMapper'],
        21 => ['ncd_cooperative_registration_pacs', 'PACS Details', 'pacsMapper'],

        9 => ['ncd_cooperative_registration_fishery', 'Fishery Details', fn($r) => [
            'annual_fish_catch'    => nullNum($r['annual_fish_catch']   ?? null),
            'credit_facility'      => $r['credit_facility']            ?? null,
            'total_credit_provided'=> nullNum($r['total_credit_provided'] ?? null),
            'fuel_distribution'    => $r['fuel_distribution']          ?? null,
            'marketing'            => $r['marketing']                  ?? null,
            'cold_storage'         => $r['cold_storage']               ?? null,
            'transportation'       => $r['transportation']             ?? null,
            'other_facility'       => $r['other_facility']             ?? null,
            'is_fpo_fisheries'     => $r['is_fpo_fisheries']           ?? null,
            'fpo_fisheries_details'=> $r['fpo_fisheries_details']      ?? null,
        ]],

        14 => ['ncd_cooperative_registrations_handicraft', 'Handicraft Details', fn($r) => [
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'individual_member'        => $r['individual_member']        ?? null,
            'institutional_member'     => $r['institutional_member']     ?? null,
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'type_raw'                 => $r['type_raw']                 ?? null,
            'type_produce'             => $r['type_produce']             ?? null,
            'common_work_place'        => $r['common_work_place']        ?? null,
            'workplace_operate'        => $r['workplace_operate']        ?? null,
            'is_work_by_member'        => $r['is_work_by_member']        ?? null,
            'is_training_provide'      => $r['is_training_provide']      ?? null,
            'is_raw_provide'           => $r['is_raw_provide']           ?? null,
            'is_raw_easy_avail'        => $r['is_raw_easy_avail']        ?? null,
            'is_waste_generate'        => $r['is_waste_generate']        ?? null,
            'is_waste_facility'        => $r['is_waste_facility']        ?? null,
            'is_operate_retail'        => $r['is_operate_retail']        ?? null,
            'no_of_retail'             => $r['no_of_retail']             ?? null,
            'is_product_sale_out'      => $r['is_product_sale_out']      ?? null,
            'facilities'               => $r['facilities']               ?? null,
        ]],

        11 => ['ncd_cooperative_registrations_handloom', 'Handloom Details', fn($r) => [
            'has_building'             => $r['has_building']              ?? null,
            'building_type'            => $r['building_type']             ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'individual_member'        => $r['individual_member']         ?? null,
            'institutional_member'     => $r['institutional_member']      ?? null,
            'power_loom_type'          => $r['power_loom_type']           ?? null,
            'hand_loom_type'           => $r['hand_loom_type']            ?? null,
            'no_of_loom'               => $r['no_of_loom']               ?? null,
            'raw_product_taken'        => $r['raw_product_taken']         ?? null,
            'raw_material_available'   => $r['raw_material_available']    ?? null,
            'waste_generate'           => $r['waste_generate']            ?? null,
            'waste_available'          => $r['waste_available']           ?? null,
            'operate_retail'           => $r['operate_retail']            ?? null,
            'no_of_retail'             => $r['no_of_retail']              ?? null,
            'product_sale_out'         => $r['product_sale_out']          ?? null,
            'operated_member_themself' => $r['operated_member_themself']  ?? null,
            'is_user_work_divide'      => $r['is_user_work_divide']       ?? null,
        ]],

        12 => ['ncd_cooperative_registrations_housing', 'Housing Details', fn($r) => [
            'type_society'                  => $r['type_society']                  ?? null,
            'has_building'                  => $r['has_building']                  ?? null,
            'building_type'                 => $r['building_type']                 ?? null,
            'has_land'                      => $r['has_land']                      ?? null,
            'authorised_share'              => nullNum($r['authorised_share']              ?? null),
            'paid_up_members'               => nullNum($r['paid_up_members']               ?? null),
            'paid_up_government_bodies'     => nullNum($r['paid_up_government_bodies']     ?? null),
            'paid_up_total'                 => nullNum($r['paid_up_total']                 ?? null),
            'annual_turn_over'              => nullNum($r['annual_turn_over']              ?? null),
            'annual_expenses'               => nullNum($r['annual_expenses']               ?? null),
            'loan_facilities'               => $r['loan_facilities']               ?? null,
            'number_of_houses_audit_year'   => $r['number_of_houses_audit_year']   ?? null,
            'number_of_houses_during_year'  => $r['number_of_houses_during_year']  ?? null,
            'number_of_houses_construction' => $r['number_of_houses_construction'] ?? null,
            'facilities'                    => $r['facilities']                    ?? null,
        ]],

        13 => ['ncd_cooperative_registrations_jute', 'Jute Details', fn($r) => [
            'has_building'       => $r['has_building']      ?? null,
            'building_type'      => $r['building_type']     ?? null,
            'authorised_share'   => nullNum($r['authorised_share'] ?? null),
            'annual_turn_over'   => nullNum($r['annual_turn_over'] ?? null),
            'type_raw'           => $r['type_raw']          ?? null,
            'type_produce'       => $r['type_produce']      ?? null,
            'common_work_place'  => $r['common_work_place'] ?? null,
            'workplace_operate'  => $r['workplace_operate'] ?? null,
            'is_work_by_member'  => $r['is_work_by_member'] ?? null,
            'is_training_provide'=> $r['is_training_provide']?? null,
            'is_raw_provide'     => $r['is_raw_provide']    ?? null,
            'is_raw_easy_avail'  => $r['is_raw_easy_avail'] ?? null,
            'is_waste_generate'  => $r['is_waste_generate'] ?? null,
            'is_waste_facility'  => $r['is_waste_facility'] ?? null,
            'is_operate_retail'  => $r['is_operate_retail'] ?? null,
            'no_of_retail'       => $r['no_of_retail']      ?? null,
            'is_product_sale_out'=> $r['is_product_sale_out']?? null,
            'facilities'         => $r['facilities']        ?? null,
        ]],

        16 => ['ncd_cooperative_registrations_labour', 'Labour Details', fn($r) => [
            'type_society'                    => $r['type_society']                    ?? null,
            'has_building'                    => $r['has_building']                    ?? null,
            'building_type'                   => $r['building_type']                   ?? null,
            'authorised_share'                => nullNum($r['authorised_share']                ?? null),
            'paid_up_members'                 => nullNum($r['paid_up_members']                 ?? null),
            'paid_up_government_bodies'       => nullNum($r['paid_up_government_bodies']       ?? null),
            'paid_up_total'                   => nullNum($r['paid_up_total']                   ?? null),
            'annual_turn_over'                => nullNum($r['annual_turn_over']                ?? null),
            'annual_expenses'                 => nullNum($r['annual_expenses']                 ?? null),
            'work_allot_state_dist_federation'=> $r['work_allot_state_dist_federation']?? null,
            'work_guide_state_dist_federation'=> $r['work_guide_state_dist_federation']?? null,
            'concession_state_gov'            => $r['concession_state_gov']            ?? null,
            'concession_centre_gov'           => $r['concession_centre_gov']           ?? null,
            'facilities'                      => $r['facilities']                      ?? null,
        ]],

        17 => ['ncd_cooperative_registrations_livestock', 'Livestock Details', fn($r) => [
            'type_society'             => $r['type_society']             ?? null,
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'individual_member'        => nullNum($r['individual_member']        ?? null),
            'institutional_member'     => nullNum($r['institutional_member']     ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'type_produce'             => $r['type_produce']             ?? null,
            'common_work_place'        => $r['common_work_place']        ?? null,
            'is_work_by_member'        => $r['is_work_by_member']        ?? null,
            'is_training_provide'      => $r['is_training_provide']      ?? null,
            'is_poultry_feed'          => $r['is_poultry_feed']          ?? null,
            'is_collected_from_member' => $r['is_collected_from_member'] ?? null,
            'is_waste_facility'        => $r['is_waste_facility']        ?? null,
            'is_operate_retail'        => $r['is_operate_retail']        ?? null,
            'no_of_retail'             => $r['no_of_retail']             ?? null,
            'is_product_sale_out'      => $r['is_product_sale_out']      ?? null,
            'facilities'               => $r['facilities']               ?? null,
        ]],

        18 => ['ncd_cooperative_registrations_marketing', 'Marketing Details', fn($r) => [
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'has_land'                 => $r['has_land']                 ?? null,
            'has_warehouses'           => $r['has_warehouses']           ?? null,
            'capacity_warehouses'      => nullNum($r['capacity_warehouses']      ?? null),
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'annual_expenses'          => nullNum($r['annual_expenses']          ?? null),
            'liecense_to_sell'         => $r['liecense_to_sell']         ?? null,
            'sell_the_item'            => $r['sell_the_item']            ?? null,
        ]],

        19 => ['ncd_cooperative_registrations_cmiscellaneous', 'Misc Credit Details', fn($r) => [
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'total_deposit'            => nullNum($r['total_deposit']            ?? null),
            'loan_outstanding'         => nullNum($r['loan_outstanding']         ?? null),
            'individual_member'        => $r['individual_member']        ?? null,
            'institutional_member'     => $r['institutional_member']     ?? null,
            'facilities'               => $r['facilities']               ?? null,
        ]],

        20 => ['ncd_cooperative_registrations_miscellaneous', 'Misc Non-Credit Details', fn($r) => [
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
        ]],

        22 => ['ncd_cooperative_registrations_multi', 'Multi Details', fn($r) => [
            'sec_activity'             => $r['sec_activity']             ?? null,
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'has_storage'              => $r['has_storage']              ?? null,
            'storage_capacity'         => nullNum($r['storage_capacity'] ?? null),
            'provide_raw'              => $r['provide_raw']              ?? null,
            'guidance_by_member'       => $r['guidance_by_member']       ?? null,
            'is_operate_retail'        => $r['is_operate_retail']        ?? null,
            'no_of_retail'             => $r['no_of_retail']             ?? null,
            'is_product_sale_out'      => $r['is_product_sale_out']      ?? null,
            'facilities'               => $r['facilities']               ?? null,
        ]],

        23 => ['ncd_cooperative_registrations_sericulture', 'Sericulture Details', fn($r) => [
            'type_society'                  => $r['type_society']                  ?? null,
            'has_building'                  => $r['has_building']                  ?? null,
            'building_type'                 => $r['building_type']                 ?? null,
            'authorised_share'              => nullNum($r['authorised_share']              ?? null),
            'annual_turn_over'              => nullNum($r['annual_turn_over']              ?? null),
            'common_work_place'             => $r['common_work_place']             ?? null,
            'no_rear_house'                 => $r['no_rear_house']                 ?? null,
            'is_work_by_member'             => $r['is_work_by_member']             ?? null,
            'is_training_provide'           => $r['is_training_provide']           ?? null,
            'is_rear_appliance'             => $r['is_rear_appliance']             ?? null,
            'is_mulberry_easy_available'    => $r['is_mulberry_easy_available']    ?? null,
            'is_cleaning_facility_cocoon'   => $r['is_cleaning_facility_cocoon']   ?? null,
            'is_spinning_weav'              => $r['is_spinning_weav']              ?? null,
            'is_waste_facility'             => $r['is_waste_facility']             ?? null,
            'is_operate_retail'             => $r['is_operate_retail']             ?? null,
            'no_of_retail'                  => $r['no_of_retail']                  ?? null,
            'facilities'                    => $r['facilities']                    ?? null,
        ]],

        24 => ['ncd_cooperative_registrations_social', 'Social Details', fn($r) => [
            'type_society'               => $r['type_society']               ?? null,
            'has_building'               => $r['has_building']               ?? null,
            'building_type'              => $r['building_type']              ?? null,
            'authorised_share'           => nullNum($r['authorised_share']           ?? null),
            'paid_up_members'            => nullNum($r['paid_up_members']            ?? null),
            'paid_up_government_bodies'  => nullNum($r['paid_up_government_bodies']  ?? null),
            'paid_up_total'              => nullNum($r['paid_up_total']              ?? null),
            'annual_turn_over'           => nullNum($r['annual_turn_over']           ?? null),
            'type_social_culture_activity'=> $r['type_social_culture_activity'] ?? null,
            'has_common'                 => $r['has_common']                 ?? null,
            'is_operate_by_member'       => $r['is_operate_by_member']       ?? null,
            'guidance_by_member'         => $r['guidance_by_member']         ?? null,
            'is_operate_vehicle'         => $r['is_operate_vehicle']         ?? null,
            'no_of_vehicle'              => $r['no_of_vehicle']              ?? null,
            'facilities'                 => $r['facilities']                 ?? null,
        ]],

        25 => ['ncd_cooperative_registrations_sugar', 'Sugar Details', fn($r) => [
            'has_building'                => $r['has_building']               ?? null,
            'building_type'               => $r['building_type']              ?? null,
            'authorised_share'            => nullNum($r['authorised_share']            ?? null),
            'paid_up_members'             => nullNum($r['paid_up_members']             ?? null),
            'paid_up_government_bodies'   => nullNum($r['paid_up_government_bodies']   ?? null),
            'paid_up_total'               => nullNum($r['paid_up_total']               ?? null),
            'suger_mills_no'              => $r['suger_mills_no']             ?? null,
            'build_up_area'               => nullNum($r['build_up_area']               ?? null),
            'open_land_area'              => nullNum($r['open_land_area']              ?? null),
            'total_area'                  => nullNum($r['total_area']                  ?? null),
            'liecensed_capicity'          => nullNum($r['liecensed_capicity']          ?? null),
            'installed_capicity'          => nullNum($r['installed_capicity']          ?? null),
            'crushing_period_start'       => dateOrNull($r['crushing_period_start']    ?? null),
            'crushing_period_end'         => dateOrNull($r['crushing_period_end']      ?? null),
            'product_produced'            => $r['product_produced']           ?? null,
            'retail_shops'                => $r['retail_shops']               ?? null,
            'retail_shops_no'             => $r['retail_shops_no']            ?? null,
            'sugercane_input_provided'    => $r['sugercane_input_provided']   ?? null,
            'loan_facility'               => $r['loan_facility']              ?? null,
            'waste_management'            => $r['waste_management']           ?? null,
            'central_government_benefits' => $r['central_government_benefits']?? null,
            'state_government_benefits'   => $r['state_government_benefits']  ?? null,
            'annual_turn_over'            => nullNum($r['annual_turn_over']            ?? null),
        ]],

        26 => ['ncd_cooperative_registrations_tourism', 'Tourism Details', fn($r) => [
            'type_society'             => $r['type_society']             ?? null,
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'paid_up_members'          => nullNum($r['paid_up_members']          ?? null),
            'paid_up_government_bodies'=> nullNum($r['paid_up_government_bodies']?? null),
            'paid_up_total'            => nullNum($r['paid_up_total']            ?? null),
            'individual_member'        => $r['individual_member']        ?? null,
            'institutional_member'     => $r['institutional_member']     ?? null,
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'pool_resource'            => $r['pool_resource']            ?? null,
            'any_resource_taken'       => $r['any_resource_taken']       ?? null,
            'is_right_vested'          => $r['is_right_vested']          ?? null,
            'facilities'               => $r['facilities']               ?? null,
        ]],

        27 => ['ncd_cooperative_registrations_transport', 'Transport Details', fn($r) => [
            'type_society'              => $r['type_society']              ?? null,
            'has_building'              => $r['has_building']              ?? null,
            'building_type'             => $r['building_type']             ?? null,
            'authorised_share'          => nullNum($r['authorised_share']          ?? null),
            'paid_up_members'           => nullNum($r['paid_up_members']           ?? null),
            'paid_up_government_bodies' => nullNum($r['paid_up_government_bodies'] ?? null),
            'paid_up_total'             => nullNum($r['paid_up_total']             ?? null),
            'annual_turn_over'          => nullNum($r['annual_turn_over']          ?? null),
            'individual_member'         => $r['individual_member']         ?? null,
            'institutional_member'      => $r['institutional_member']      ?? null,
            'type_owner'                => $r['type_owner']                ?? null,
            'bus_type_detail'           => $r['bus_type_detail']           ?? null,
            'truck_type_detail'         => $r['truck_type_detail']         ?? null,
            'other_type_detail'         => $r['other_type_detail']         ?? null,
            'no_passenger_vehicle'      => $r['no_passenger_vehicle']      ?? null,
            'no_member_travel'          => $r['no_member_travel']          ?? null,
            'no_freight_vehicle'        => $r['no_freight_vehicle']        ?? null,
            'quantity_good_transport'   => $r['quantity_good_transport']   ?? null,
            'member_themself'           => $r['member_themself']           ?? null,
            'is_user_transport_facility'=> $r['is_user_transport_facility']?? null,
        ]],

        28 => ['ncd_cooperative_registrations_tribal', 'Tribal Details', fn($r) => [
            'type_society'                 => $r['type_society']                 ?? null,
            'has_building'                 => $r['has_building']                 ?? null,
            'building_type'                => $r['building_type']                ?? null,
            'authorised_share'             => nullNum($r['authorised_share']             ?? null),
            'paid_up_members'              => nullNum($r['paid_up_members']              ?? null),
            'paid_up_government_bodies'    => nullNum($r['paid_up_government_bodies']    ?? null),
            'paid_up_total'                => nullNum($r['paid_up_total']                ?? null),
            'annual_turn_over'             => nullNum($r['annual_turn_over']             ?? null),
            'state_district_federation'    => $r['state_district_federation']    ?? null,
            'society_provide_raw_material' => $r['society_provide_raw_material'] ?? null,
            'facilities'                   => $r['facilities']                   ?? null,
        ]],

        29 => ['ncd_cooperative_registrations_ucb', 'UCB Details', fn($r) => [
            'has_building'             => $r['has_building']             ?? null,
            'building_type'            => $r['building_type']            ?? null,
            'ucb_branch'               => $r['ucb_branch']               ?? null,
            'has_nafcub'               => $r['has_nafcub']               ?? null,
            'authorised_share'         => nullNum($r['authorised_share']         ?? null),
            'annual_turn_over'         => nullNum($r['annual_turn_over']         ?? null),
            'annual_income'            => nullNum($r['annual_income']            ?? null),
            'annual_ucb_expenditr'     => nullNum($r['annual_ucb_expenditr']     ?? null),
            'asset_ucb'                => nullNum($r['asset_ucb']                ?? null),
            'liability_ucb'            => nullNum($r['liability_ucb']            ?? null),
            'total_deposit'            => nullNum($r['total_deposit']            ?? null),
            'loan_outstanding'         => nullNum($r['loan_outstanding']         ?? null),
            'is_gov_scheme_implemented'=> $r['is_gov_scheme_implemented'] ?? null,
            'is_computerized'          => $r['is_computerized']          ?? null,
            'no_computer_working'      => $r['no_computer_working']      ?? null,
            'have_ifsc'                => $r['have_ifsc']                ?? null,
            'have_corebanking'         => $r['have_corebanking']         ?? null,
            'have_doorstepservice'     => $r['have_doorstepservice']     ?? null,
            'is_aeps'                  => $r['is_aeps']                  ?? null,
            'offer_debitcard'          => $r['offer_debitcard']          ?? null,
            'have_internetbanking'     => $r['have_internetbanking']     ?? null,
            'offer_creditcard'         => $r['offer_creditcard']         ?? null,
            'cibil_membership'         => $r['cibil_membership']         ?? null,
            'conducting_gab'           => $r['conducting_gab']           ?? null,
            'cgtmsemli_member'         => $r['cgtmsemli_member']         ?? null,
            'is_saf_to_cust'           => $r['is_saf_to_cust']           ?? null,
            'networth'                 => nullNum($r['networth']                 ?? null),
            'fswm_comp'                => $r['fswm_comp']                ?? null,
        ]],

        30 => ['ncd_cooperative_registrations_wocoop', 'Women Welfare Details', fn($r) => [
            'type_society'        => $r['type_society']        ?? null,
            'has_building'        => $r['has_building']        ?? null,
            'building_type'       => $r['building_type']       ?? null,
            'authorised_share'    => nullNum($r['authorised_share']    ?? null),
            'annual_turn_over'    => nullNum($r['annual_turn_over']    ?? null),
            'is_raw_material_taken'=> $r['is_raw_material_taken']?? null,
            'facilities'          => $r['facilities']          ?? null,
        ]],

        31 => ['ncd_cooperative_registrations_khadi_gram', 'Khadi Gram Details', fn($r) => [
            'has_building'                              => $r['has_building']                              ?? null,
            'building_type'                             => $r['building_type']                             ?? null,
            'authorised_share'                          => nullNum($r['authorised_share']                          ?? null),
            'paid_up_members'                           => nullNum($r['paid_up_members']                           ?? null),
            'paid_up_government_bodies'                 => nullNum($r['paid_up_government_bodies']                 ?? null),
            'paid_up_total'                             => nullNum($r['paid_up_total']                             ?? null),
            'annual_turn_over'                          => nullNum($r['annual_turn_over']                          ?? null),
            'individual_member'                         => $r['individual_member']                         ?? null,
            'institutional_member'                      => $r['institutional_member']                      ?? null,
            'power_loom_type'                           => $r['power_loom_type']                           ?? null,
            'hand_loom_type'                            => $r['hand_loom_type']                            ?? null,
            'hand_loom_other_type'                      => $r['hand_loom_other_type']                      ?? null,
            'no_of_loom'                                => $r['no_of_loom']                                ?? null,
            'raw_product_taken'                         => $r['raw_product_taken']                         ?? null,
            'raw_material_available'                    => $r['raw_material_available']                    ?? null,
            'waste_generate'                            => $r['waste_generate']                            ?? null,
            'waste_available'                           => $r['waste_available']                           ?? null,
            'operate_retail'                            => $r['operate_retail']                            ?? null,
            'no_of_retail'                              => $r['no_of_retail']                              ?? null,
            'product_sale_out'                          => $r['product_sale_out']                          ?? null,
            'operated_member_themself'                  => $r['operated_member_themself']                  ?? null,
            'is_user_work_divide'                       => $r['is_user_work_divide']                       ?? null,
            'type_of_activities_khadi_gram'             => $r['type_of_activities_khadi_gram']             ?? null,
            'do_you_want_to_enter_type_products_produced'=> $r['do_you_want_to_enter_type_products_produced']?? null,
            'fswm_comp'                                 => $r['fswm_comp']                                 ?? null,
        ]],
    ];
}


// ╔══════════════════════════════════════════════════════════╗
// ║                  HELPER FUNCTIONS                        ║
// ╚══════════════════════════════════════════════════════════╝

/** Generic upsert: insert or update on duplicate of $uniqueKey */
function upsert(PDO $pdo, string $table, array $uniqueRow, array $dataRow): void {
    $allData = array_merge($uniqueRow, $dataRow);
    $cols    = array_keys($allData);
    $plc     = array_map(fn($c) => ":$c", $cols);
    $upd     = array_map(fn($c) => "$c=VALUES($c)", array_keys($dataRow));

    $sql = sprintf(
        "INSERT INTO %s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s",
        $table,
        implode(',', $cols),
        implode(',', $plc),
        implode(',', $upd)
    );
    $stmt = $pdo->prepare($sql);
    $bound = [];
    foreach ($allData as $k => $v) {
        $bound[":$k"] = $v;
    }
    $stmt->execute($bound);
}

/** Pull a misc-level endpoint and upsert into table */
function sync_misc_level(
    PDO $pdo, int $level, string $table,
    string $resultKey, callable $mapper, ?string $uniqueField
): void {
    $data = api_get('/en/Api/apimiscellanousdata', ['state' => STATE_CODE, 'level' => $level]);
    if (empty($data['result'])) {
        log_msg("  Level $level ($table): no data.");
        return;
    }

    $n = 0;
    foreach ($data['result'] as $item) {
        $r   = $item[$resultKey] ?? reset($item);
        $row = $mapper($r);
        if ($uniqueField && isset($row[$uniqueField])) {
            upsert($pdo, $table, [$uniqueField => $row[$uniqueField]], $row);
        } else {
            // bulk insert without dedup key
            $cols = array_keys($row);
            $plc  = array_map(fn($c) => ":$c", $cols);
            $stmt = $pdo->prepare(
                "INSERT IGNORE INTO $table (" . implode(',', $cols) . ") VALUES (" . implode(',', $plc) . ")"
            );
            $stmt->execute(array_combine($plc, array_values($row)));
        }
        $n++;
    }
    log_saved($pdo, "$table (level $level)", $n);
}

// Shared mapper for PACS / FSS / LAMPS
function pacsMapper(array $r): array {
    return [
        'has_building'                  => $r['has_building']                  ?? null,
        'building_type'                 => $r['building_type']                 ?? null,
        'fertilizer_distribution'       => $r['fertilizer_distribution']       ?? null,
        'fertilizer_distribution_qty'   => $r['fertilizer_distribution_qty']   ?? null,
        'fertilizer_distribution_details'=> $r['fertilizer_distribution_details']??null,
        'pesticide_distribution'        => $r['pesticide_distribution']        ?? null,
        'pesticide_distribution_qty'    => $r['pesticide_distribution_qty']    ?? null,
        'seed_distribution'             => $r['seed_distribution']             ?? null,
        'seed_distribution_qty'         => $r['seed_distribution_qty']         ?? null,
        'fair_price'                    => $r['fair_price']                    ?? null,
        'fair_price_qty'                => $r['fair_price_qty']                ?? null,
        'fair_price_details'            => $r['fair_price_details']            ?? null,
        'is_foodgrains'                 => $r['is_foodgrains']                 ?? null,
        'foodgrains_qty'                => $r['foodgrains_qty']                ?? null,
        'agricultural_implements'       => $r['agricultural_implements']       ?? null,
        'agricultural_implements_text'  => $r['agricultural_implements_text']  ?? null,
        'dry_storage'                   => $r['dry_storage']                   ?? null,
        'dry_storage_capicity'          => nullNum($r['dry_storage_capicity']  ?? null),
        'cold_storage'                  => $r['cold_storage']                  ?? null,
        'cold_storage_capicity'         => nullNum($r['cold_storage_capicity'] ?? null),
        'milk_unit'                     => $r['milk_unit']                     ?? null,
        'milk_capicity_unit'            => $r['milk_capicity_unit']            ?? null,
        'food_processing'               => $r['food_processing']               ?? null,
        'food_processing_type'          => $r['food_processing_type']          ?? null,
        'other_facility'                => $r['other_facility']                ?? null,
        'is_socitey_has_land'           => $r['is_socitey_has_land']           ?? null,
        'pack_involved_fish_catch'      => $r['pack_involved_fish_catch']      ?? null,
        'pack_annual_fish_catch'        => nullNum($r['pack_annual_fish_catch'] ?? null),
        'pack_total_outstanding_loan'   => nullNum($r['pack_total_outstanding_loan']?? null),
        'pack_revenue_non_credit'       => nullNum($r['pack_revenue_non_credit']?? null),
        'is_lgs_program'                => $r['is_lgs_program']                ?? null,
        'lgs_capacity'                  => nullNum($r['lgs_capacity']          ?? null),
        'is_csc'                        => $r['is_csc']                        ?? null,
        'csc_revenue'                   => $r['csc_revenue']                   ?? null,
        'csc_details'                   => $r['csc_details']                   ?? null,
        'is_fpo'                        => $r['is_fpo']                        ?? null,
        'fpo_details'                   => $r['fpo_details']                   ?? null,
        'is_lpg_distributership'        => $r['is_lpg_distributership']        ?? null,
        'lpg_distributership_details'   => $r['lpg_distributership_details']   ?? null,
        'is_bcp_pump'                   => $r['is_bcp_pump']                   ?? null,
        'bcp_pump_details'              => $r['bcp_pump_details']              ?? null,
        'is_dpp_diesel'                 => $r['is_dpp_diesel']                 ?? null,
        'dpp_diesel_details'            => $r['dpp_diesel_details']            ?? null,
        'is_jak'                        => $r['is_jak']                        ?? null,
        'jak_qty'                       => $r['jak_qty']                       ?? null,
        'is_pmksk'                      => $r['is_pmksk']                      ?? null,
        'pmksk_details'                 => $r['pmksk_details']                 ?? null,
        'is_paani_samity'               => $r['is_paani_samity']               ?? null,
        'paani_samity_details'          => $r['paani_samity_details']          ?? null,
        'is_pm_kusum_scheme'            => $r['is_pm_kusum_scheme']            ?? null,
        'pm_kusum_scheme_details'       => $r['pm_kusum_scheme_details']       ?? null,
    ];
}

/** Make an API GET call and return decoded JSON */
function api_get(string $path, array $extra = []): array {
    $params = array_merge(['key' => API_KEY], $extra);
    $url    = API_BASE . $path . '?' . http_build_query($params);
    $ctx    = stream_context_create(['http' => [
        'timeout' => 120,
        'header'  => "Accept: application/json\r\n",
    ]]);
    $raw = @file_get_contents($url, false, $ctx);
    if ($raw === false) {
        log_msg("  [WARN] API call failed: $url");
        return [];
    }
    $decoded = json_decode($raw, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        log_msg("  [WARN] JSON decode error for $url");
        return [];
    }
    if (($decoded['status'] ?? '') !== 'Success') {
        log_msg("  [WARN] Non-success for $url : " . json_encode($decoded));
        return [];
    }
    return $decoded;
}

/** Parse a date string that may come in various formats; return MySQL date or null */
function dateOrNull(?string $v): ?string {
    if (empty($v) || $v === 'null') return null;
    // Handle ISO 8601:  2025-04-08T00:00:00+00:00
    if (str_contains($v, 'T')) {
        $ts = strtotime($v);
        return $ts ? date('Y-m-d', $ts) : null;
    }
    // Handle DD-MM-YYYY
    if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $v, $m)) {
        return "{$m[3]}-{$m[2]}-{$m[1]}";
    }
    // Try PHP default
    $ts = strtotime($v);
    return $ts ? date('Y-m-d', $ts) : null;
}

/** Convert empty string / "null" to PHP null for numeric columns */
function nullNum(mixed $v): ?float {
    if ($v === null || $v === '' || $v === 'null') return null;
    return (float)$v;
}

/** Convert empty string to null */
function nvl(mixed $v): mixed {
    return ($v === '' || $v === 'null') ? null : $v;
}

function log_msg(string $msg): void {
    $ts = date('[Y-m-d H:i:s]');
    echo "$ts $msg\n";
}

function log_saved(PDO $pdo, string $apiName, int $n): void {
    log_msg("  Saved $n rows → $apiName");
    $pdo->prepare("INSERT INTO ncd_sync_log (api_name, records_saved) VALUES (?,?)")
        ->execute([$apiName, $n]);
}
