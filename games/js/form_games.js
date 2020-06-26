let buttonHome = document.getElementById('rad-home');
let buttonVisitante = document.getElementById('rad-guest');

window.onload = function () {
    if (window.matchMedia("(max-width: 768px)")) {
        document.getElementsByClassName('box-score-mobile').required;
        document.getElementsByClassName('box-score-desktop').disabled = true;
    } else {
        document.getElementsByClassName('box-score-mobile').required;
        document.getElementsByClassName('box-score-desktop').disabled = false;
    }

    if (document.getElementById('club_name').value == document.getElementById('home').value) {
        document.getElementById('rad-home').checked = true;
        document.getElementById('home').readOnly = true;
    }

    if (document.getElementById('club_name').value == document.getElementById('guest').value) {
        document.getElementById('rad-guest').checked = true;
        document.getElementById('guest').readOnly = true;
    }
}

// Cicked in home club
buttonHome.addEventListener('click', function () {
    document.getElementById('stadium').value = document.getElementById('stadium_club').value;
    document.getElementById('stadium').readOnly = true;
    document.getElementById('home').value = document.getElementById('club_name').value;
    document.getElementById('home').readOnly = true;
    document.getElementById('guest').value = null;
    document.getElementById('guest').readOnly = false;
});

// Clicked in guest club
buttonVisitante.addEventListener('click', function () {
    document.getElementById('guest').value = document.getElementById('club_name').value;
    document.getElementById('guest').readOnly = true;
    document.getElementById('stadium').value = '';
    document.getElementById('stadium').readOnly = false;
    document.getElementById('home').value = null;
    document.getElementById('home').readOnly = false;
});


let labelDate = document.getElementById('date');
let currentDate = new Date();
let today = currentDate.getFullYear() + '-' + String(currentDate.getMonth() + 1).padStart(2, '0') + '-' + String(currentDate.getDate()).padStart(2, '0');

// Check if date is greater than today and disabled or enabled input scores
labelDate.addEventListener('blur', function () {
    if (labelDate.value > today) {
        document.getElementById('score_home').disabled = true;
        document.getElementById('score_home').value = '';
        document.getElementById('score_guest').disabled = true;
        document.getElementById('score_guest').value = '';
    }

    if (labelDate.value <= today) {
        document.getElementById('score_home').disabled = false;
        document.getElementById('score_guest').disabled = false;
    }
});