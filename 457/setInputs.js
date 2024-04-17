function setHiddenInputs() {

    var urlParams = new URLSearchParams(window.location.search);
    var name = urlParams.get('name');
    var password = urlParams.get('password');
    var keywords = urlParams.get('keywords');


    document.getElementById('nameInput').value = name;
    document.getElementById('passwordInput').value = password;
    if (keywords != ''){
        document.getElementById('keywords').value = keywords;
    }

    return true;
}