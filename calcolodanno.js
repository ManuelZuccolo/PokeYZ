//charmeleon
let hp1 = 134;
let atk1 = 84;
let def1 = 78;
let spa1 = 132;
let spd1 = 85;
let spe1 = 145;
let element1a="fuoco";
let element2a;
let accuratezza1 = 0;
let effeto1;
let protetto1 = false;
//mewtwo
let hp2 = 182;
let atk2 = 117;
let def2 = 110;
let spa2 = 206;
let spd2 = 110;
let spe2 = 200;
let element1b="psyco";
let element2b;
let accuratezza2 = 0;
let effeto2;
let protetto2 = false;
//effetti? cose combattimento
let daTogliere1 = Math.ceil(hp1 * 0.06);
let daTogliere2 = Math.ceil(hp2 * 0.06);
let daTogliere3 = Math.ceil(hp1 * 0.12);
let daTogliere4 = Math.ceil(hp1 * 0.12);
let avvelentoturni1 = 1;
let avvelentoturni2 = 1;
let aggcrt1=0;
let aggcrt2=0;
let dannotot;
let turn=1;

function aggiornaBarraHP() {
    let hpMassimo = 182; 
    let percentuale = (hp2 / hpMassimo) * 100;
    
    document.getElementById('hpRight').style.width = percentuale + '%';
    
    document.getElementById('hpRightText').innerHTML = Math.floor(hp2) + '/' + hpMassimo;
    
    // Se vuoi aggiornare anche la barra di sinistra quando serve
    aggiornaBarraHPSinistra();
}

function turni(valut,elemento,danno,accuratezza,effet){
    if(spe1 > spe2){
        turn = 1;
        valuta(valut,elemento,danno,accuratezza,effet);
        hp2 -= dannotot;
        hp2 = Math.max(0, Math.floor(hp2));
        aggiornaBarraHP();
        if(hp2!=0){
            turn = 2;
            valuta(valut,elemento,danno,accuratezza,effet);
            hp1 -= dannotot
            hp1 = Math.max(0, Math.floor(hp2));
            aggiornaBarraHPdes();
            if(hp2==0){}
                //switch pokemon
        }
        else{}
            //switch pokemon
    }
    else if(spe2 > spe1){
        turn = 2;
        valuta(valut,elemento,danno,accuratezza,effet);
        hp1 -= dannotot;
        hp1 = Math.max(0, Math.floor(hp2));
        aggiornaBarraHPdes();
        if(hp2!=0){
            turn = 1;
            valuta(valut,elemento,danno,accuratezza,effet);
            hp2 -= dannotot;
            hp2 = Math.max(0, Math.floor(hp2));
            aggiornaBarraHP();
            if(hp2==0){}
                //switch pokemon
        }
        else{}
            //switch pokemon
    }
    else{
        let rand = randomInt(1,2);
        turn=rand;
        if(rand==1)
            rand=2;
        else
            rand=1;
    }
}

function dannototale(dannotot,elemento){
    let random;

    if(aggcrt1==0)

    if(elemento== element1a || elemento == element1b){
        dannotot *= 1.5;
    }

    random = 0.8 + (Math.random() * 0.2);

    dannotot *= random;
}

function calcolodanno(elemento,danno,effet,atk,def,protect){
    if(protect == false)
        dannotot  = (((2*50+10)/250*atk/def*danno)+2);
        //Ho trovato questa Danno = (((2 * Livello + 10) / 250) * Attacco / Difesa * Potenza) + 2

        dannotot = dannototale(dannotot,elemento)
        //effet(effet);
}


function valuta(valut,elemento,tipo,danno,accuratezza,effet){
    let damage
    let colpire;
    if(valut=="dirreto"){
        if(turn==1){
            colpire = colpire(accuratezza1,accuratezza);
        }
        else{
            colpire = colpire(accuratezza2,accuratezza);
        }
        if(tipo=="f"){
            if(turn==1){
                calcolodanno(elemento,danno,effet,atk1,def2,protetto2);
            }
            else{
                calcolodanno(elemento,danno,effet,atk2,def1,protetto1);
            }
        }
        else{
            if(turn==1){
                calcolodanno(elemento,danno,effet,spa1,spd2,protetto2);
            }
            else{
                calcolodanno(elemento,danno,effet,spa2,spd1,protetto1);
            }
        }
    }
}

function colpire(accuratezzap,accuratezza){
    if(accuratezza != "-"){
        let prob = randomInt(1,100)
        if(prob <= accuratezza){
            return true;
        }
        else return false;
    }
    else return true
}

function effeto(effet){
    if(effet=="burn30"){
        let prob = randomInt(1,100)
        if(prob <= 30)
            effeto2 = "bruciato";
    }
}

function randomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function stato(effeto1,effeto2){
    if(effeto2 == "bruciato"){
        hp2 -= daTogliere2;
        atk2 /= 2;
    }
    else if(effeto1  == "bruciato"){
        hp1 -= daTogliere1;
        atk1 /= 2;
    }
    else if(effeto1 == "avelenato")
        hp1 -= daTogliere3
    else if(effeto2 == "avelenato")
        hp2 -= daTogliere4
    else if(effeto1 == "avelanatof"){
        hp1 -= daTogliere3*avvelentoturni1
        avvelentoturni1 *= 2;
    }
    else if(effeto2 == "avelanatof"){
        hp2 -= daTogliere4*avvelentoturni2
        avvelentoturni2 *= 2;
    }
}











//Parte Giallo non cinese
/*Io avevo pensato di farla così:
    -danno fisso per entrambi, mini formula
    -turno di combattimento con pari dispari così teniamo conto dei turni di gioco (more or less)
    -controllo costante della vita di entrambi per vedere se uno vince
*/
function calcoloDanno(attacco, difesa) {
    let livello = 50;
    let potenza = 80; //potenza fissa perché non ho voglia
    
    let danno = (((2 * livello + 10) / 250) * (attacco / difesa) * potenza) + 2;  
    return Math.floor(danno);
}


function turnoCombattimento() {


    if (turno%2==1) {
        attacco1();
        if (hp2 > 0) attacco2();
    } else {
        attacco2();
        if (hp1 > 0) attacco1();
    }

    turno++;
}



function attacco1() {
    let danno = calcoloDanno(atk1, def2);
    hp2 -= danno;
    if (hp2 < 0) hp2 = 0;
    
    console.log("Charmeleon infligge " + danno + " danni!");
    console.log("HP Mewtwo: " + hp2);
}

function attacco2() {
    let danno = calcoloDanno(atk2, def1);
    hp1 -= danno;
    if (hp1 < 0) hp1 = 0;
    
    console.log("Mewtwo infligge " + danno + " danni!");
    console.log("HP Charmeleon: " + hp1);
}



function controllaVincitore() {
    if (hp1 <= 0) {
        console.log("Mewtwo ha vinto!");
        return true;
    }
    if (hp2 <= 0) {
        console.log("Charmeleon ha vinto!");
        return true;
    }
    return false;
}



function iniziaBattaglia() {
    if(spe1 > spe2) turno = 1; else turno=2;
    while (hp1 > 0 && hp2 > 0) {
        turnoCombattimento();
    }
    controllaVincitore();
}
