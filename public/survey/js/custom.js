const letterPlace = document.querySelectorAll('.first-letter-area');
const letterPicker = document.querySelectorAll('.first-letter-picker');

letterPlace.forEach((ele, i) => {
    ele.innerText = letterPicker[i].innerText.split('')[0].toUpperCase();
})