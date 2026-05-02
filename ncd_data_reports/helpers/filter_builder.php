<?php
function buildCooperativeFilters($request) {

    $where = "WHERE 1=1";

    if (!empty($request['authority_id'])) {
        $id = intval($request['authority_id']);
        $where .= " AND registration_authoritie_id = $id";
    }

    $filters = [
        'reference_year',
        'area_of_operation_id',
        'water_body_type_id',
        'is_approved',
        'functional_status',
        'is_coastal',
        'is_affiliated_union_federation',
        'financial_audit',
        'is_profit_making',
        'is_dividend_paid',
        'state_code'
    ];

    foreach ($filters as $f) {
        if (isset($request[$f]) && $request[$f] !== '') {
            $val = addslashes($request[$f]);
            $where .= " AND $f = '$val'";
        }
    }

    return $where;
}