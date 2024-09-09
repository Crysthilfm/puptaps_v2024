document.getElementById('sameAddress').addEventListener('click', equalizeValues);

function equalizeValues() {
    // Get the checkbox and input elements
    var checkbox = document.getElementById('sameAddress');
    var input1 = document.getElementById('cityAddress');
    var input2 = document.getElementById('provincialAddress');

    // If the checkbox is checked, set the value of input2 to the value of input1
    if (checkbox.checked) {
    input2.value = input1.value;
    }
}
