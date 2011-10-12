/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @Author: Arnold Sikorski
 */
var length = 8;
    /*długość hasła*/
var noPunction = true;
var randomLength = false;

/*
 *Generuje hasło
 */
function GeneratePassword() {


    var sPassword = "";




    if (randomLength) {
        length = Math.random();

        length = parseInt(length * 100);
        length = (length % 7) + 6
    }


    for (i=0; i < length; i++) {

        numI = getRandomNum();
        if (noPunction) {
            while (checkPunc(numI)) {
                numI = getRandomNum();
            }
        }

    sPassword = sPassword + String.fromCharCode(numI);
    }

return sPassword;


}
/*
 * Generuje losowy znak
 */
function getRandomNum() {
    var rndNum = Math.random()
    rndNum = parseInt(rndNum * 1000);
    rndNum = (rndNum % 94) + 33;

    return rndNum;
}
/*
 * Suma kontrolna
 */
function checkPunc(num) {

    if ((num >=33) && (num <=47)) {
        return true;
    }
    if ((num >=58) && (num <=64)) {
        return true;
    }
    if ((num >=91) && (num <=96)) {
        return true;
    }
    if ((num >=123) && (num <=126)) {
        return true;
    }

    return false;
}

