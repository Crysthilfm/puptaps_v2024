function onlyNumber(e, t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        else
            return true;
    }
    catch (err) {
        alert(err.Description);
    }
}

function onlyAlphabets(e, t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))
            return true;
        else
            return false;
    }
    catch (err) {
        alert(err.Description);
    }
}

function noSpecialCharacters(e, t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if (charCode < 48 || (charCode > 57 && charCode < 65) || (charCode > 90 && charCode < 97) || charCode > 122)
            return false;
        else
            return true;
    }
    catch (err) {
        alert(err.Description);
    }
}

function noSpecialCharactersPlusSpace(e, t) {
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode < 48 && charCode != 32) || (charCode > 57 && charCode < 65) || (charCode > 90 && charCode < 97) || charCode > 122)
            return false;
        else
            return true;
    }
    catch (err) {
        alert(err.Description);
    }
}
