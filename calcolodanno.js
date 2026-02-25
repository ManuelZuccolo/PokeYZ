let accuratezza1 = 0;
let effeto1;
let protetto1 = false;
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

function switchpokemon(){
    
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

    else
        dannotot  = (((2*50+10)/250*atk1/def2*danno)+2);

        dannotot = dannototale(dannotot,elemento)

    //effet(effet);

    hp2 = Math.max(0, Math.floor(hp2));

    aggiornaBarraHP();
}


function valuta(valut,elemento,tipo,danno,accuratezza,effet){
    let damage;
    if(valut=="dirreto"){
        if(tipo=="s"){
            if(turn==1){
                damage = calcolodanno()
            }
        }
    }
}

function colpire(elemento,danno,accuratezza,effet){
    if(accuratezza != "-"){
        let prob = randomInt(1,100)
        if(prob <= accuratezza){
            if(danno == 0)
                effeto(effet);
            else
                calcolodanno(elemento,danno,effet);
        }
    }
    else{
        if(danno == 0)
            effeto(effet);
        else
            calcolodanno(elemento,danno,effet);
    }
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
