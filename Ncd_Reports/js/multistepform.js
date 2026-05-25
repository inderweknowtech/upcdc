// JavaScript Document

const progress = (value) => {
    document.getElementsByClassName('progress-bar')[0].style.width = `${value}%`;
}

let step = document.getElementsByClassName('step');
let prevBtn = document.getElementById('prev-btn');
let nextBtn = document.getElementById('next-btn');
let submitBtn = document.getElementById('submit-btn');
let form = document.getElementsByTagName('form')[0];
let preloader = document.getElementById('preloader-wrapper');
let bodyElement = document.querySelector('body');
let succcessDiv = document.getElementById('success');

form.onsubmit = () => { return false; }

// Auto-detect step count from DOM
let stepCount = step.length - 1; // 0-based index of last step

let current_step = 0;
step[current_step].classList.add('d-block');

// Initial button state: on first step
prevBtn.classList.add('d-none');
submitBtn.classList.add('d-none');
nextBtn.classList.remove('d-none');
nextBtn.classList.add('d-inline-block');

// ------------------------------------------------------------------
// Blank stubs — actual save logic lives in the main PHP file
// ------------------------------------------------------------------
if (typeof save_draft !== 'function') {
    function save_draft() {
        // handled in main file
    }
}

// ------------------------------------------------------------------
// NEXT
// ------------------------------------------------------------------
nextBtn.addEventListener('click', () => {
    if (current_step < stepCount) {
    step[current_step].classList.remove('d-block');
    step[current_step].classList.add('d-none');

    current_step++;

    step[current_step].classList.remove('d-none');
    step[current_step].classList.add('d-block');

    // Show prev button once we move off step 0
    prevBtn.classList.remove('d-none');
    prevBtn.classList.add('d-inline-block');

    // On last step: hide Next, show Submit
    if (current_step === stepCount) {
        nextBtn.classList.remove('d-inline-block');
        nextBtn.classList.add('d-none');
        submitBtn.classList.remove('d-none');
        submitBtn.classList.add('d-inline-block');
    }

    progress((100 / stepCount) * current_step);
    save_draft();
}
});

// ------------------------------------------------------------------
// PREV
// ------------------------------------------------------------------
prevBtn.addEventListener('click', () => {
    if (current_step > 0) {
    step[current_step].classList.remove('d-block');
    step[current_step].classList.add('d-none');

    current_step--;

    step[current_step].classList.remove('d-none');
    step[current_step].classList.add('d-block');

    // Moving back from last step: show Next, hide Submit
    if (current_step < stepCount) {
        submitBtn.classList.remove('d-inline-block');
        submitBtn.classList.add('d-none');
        nextBtn.classList.remove('d-none');
        nextBtn.classList.add('d-inline-block');
    }

    // Hide prev button when back at step 0
    if (current_step === 0) {
        prevBtn.classList.remove('d-inline-block');
        prevBtn.classList.add('d-none');
    }

    progress((100 / stepCount) * current_step);
    save_draft();
}
});

// ------------------------------------------------------------------
// SUBMIT
// ------------------------------------------------------------------
submitBtn.addEventListener('click', () => {
    preloader.classList.add('d-block');

const timer = ms => new Promise(res => setTimeout(res, ms));

timer(1000)
    .then(() => {
    bodyElement.classList.add('loaded');
})
.then(() => {
    step[current_step].classList.remove('d-block');
step[current_step].classList.add('d-none');
nextBtn.classList.remove('d-inline-block');
nextBtn.classList.add('d-none');
submitBtn.classList.remove('d-inline-block');
submitBtn.classList.add('d-none');
prevBtn.classList.remove('d-inline-block');
prevBtn.classList.add('d-none');
succcessDiv.classList.remove('d-none');
succcessDiv.classList.add('d-block');
});
});