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


function debolezze(elemento,ele1,ele2){
    switch(elemento){
        case "Fire" : 
        if(ele1 == "Grass" || ele1 == "Steel" || ele1 == "Bug" || ele1 == "Ice")
            dannotot *= 2;
        else if(ele1=="Fire" || ele1 == "Water" || ele1 == "Dragon")
            dannotot *= 0.5

        if(ele2 == "Grass" || ele2 == "Steel" || ele2 == "Bug" || ele2 == "Ice")
            dannotot *= 2;
        else if(ele2=="Fire" || ele2 == "Water" || ele2 == "Dragon")
            dannotot *= 0.5
        break;

        case "Normal" : 
        if(ele1=="Rock" || ele1 == "Steel")
            dannotot *= 0.5
        else if(ele1 == "Gost")
            dannotot = 0;

        if(ele2=="Rock" || ele2 == "Steel")
            dannotot *= 0.5
        else if(ele2 == "Gost")
            dannotot = 0;
        break;

        case "Fighting" : 
        if(ele1 == "Normal" || ele1 == "Steel" || ele1 == "Dark" || ele1 == "Ice" || ele1=="Rock")
            dannotot *= 2;
        else if(ele1=="Flying" || ele1 == "Poison" || ele1 == "Bug" || ele1=="Psychic" || ele1=="Fairy")
            dannotot *= 0.5
        else if(ele1 == "Gost")
            dannotot = 0;

        if(ele2 == "Normal" || ele2 == "Steel" || ele2 == "Dark" || ele2 == "Ice" || ele2=="Rock")
            dannotot *= 2;
        else if(ele2=="Flying" || ele2 == "Poison" || ele2 == "Bug" || ele2=="Psychic" || ele2=="Fairy")
            dannotot *= 0.5
        else if(ele2 == "Gost")
            dannotot = 0;
        break;

        case "Flying" : 
        if(ele1 == "Fighting" || ele1 == "Bug" || ele1 == "Bug" || ele1 == "Grass")
            dannotot *= 2;
        else if(ele1=="Rock" || ele1 == "Steel" || ele1 == "Electric")
            dannotot *= 0.5

        if(ele2 == "Fighting" || ele2 == "Bug" || ele2 == "Bug" || ele2 == "Grass")
            dannotot *= 2;
        else if(ele2=="Rock" || ele2 == "Steel" || ele2 == "Electric")
            dannotot *= 0.5
        break;

        case "Poison" : 
        if(ele1 == "Grass" || ele1 == "Fairy")
            dannotot *= 2;
        else if(ele1=="Rock" || ele1 == "Poison" || ele1 == "Ground" || ele1=="Gost")
            dannotot *= 0.5
        else if(ele1 == "steel")
            dannotot = 0;

        if(ele2 == "Grass" || ele2 == "Fairy")
            dannotot *= 2;
        else if(ele2=="Rock" || ele2 == "Poison" || ele2 == "Ground" || ele2=="Gost")
            dannotot *= 0.5
        else if(ele2 == "steel")
            dannotot = 0;
        break;

        case "Ground" : 
        if(ele1 == "Electric" || ele1 == "Steel" || ele1 == "Fire" || ele1=="Poison" || ele1=="Rock")
            dannotot *= 2;
        else if(ele1=="Bug" || ele1 == "Grass")
            dannotot *= 0.5
        else if(ele1 == "Flying")
            dannotot = 0;

        if(ele2 == "Electric" || ele2 == "Steel" || ele2 == "Fire" || ele2=="Poison" || ele2=="Rock")
            dannotot *= 2;
        else if(ele2=="Bug" || ele2 == "Grass")
            dannotot *= 0.5
        else if(ele2 == "Flying")
            dannotot = 0;
        break;

        case "Water":
        if(ele1 == "Fire" || ele1 == "Ground" || ele1 == "Rock")
            dannotot *= 2;
        else if(ele1 == "Water" || ele1 == "Grass" || ele1 == "Dragon")
            dannotot *= 0.5;
            
        if(ele2 == "Fire" || ele2 == "Ground" || ele2 == "Rock")
            dannotot *= 2;
        else if(ele2 == "Water" || ele2 == "Grass" || ele2 == "Dragon")
            dannotot *= 0.5;
        break;

        case "Electric":
        if(ele1 == "Water" || ele1 == "Flying")
            dannotot *= 2;
        else if(ele1 == "Electric" || ele1 == "Grass" || ele1 == "Dragon")
            dannotot *= 0.5;
        else if(ele1 == "Ground")
            dannotot = 0;
            
        if(ele2 == "Water" || ele2 == "Flying")
            dannotot *= 2;
        else if(ele2 == "Electric" || ele2 == "Grass" || ele2 == "Dragon")
            dannotot *= 0.5;
        else if(ele2 == "Ground")
            dannotot = 0;
        break;

         case "Grass":
        if(ele1 == "Water" || ele1 == "Ground" || ele1 == "Rock")
            dannotot *= 2;
        else if(ele1 == "Fire" || ele1 == "Grass" || ele1 == "Poison" || ele1 == "Flying" || ele1 == "Bug" || ele1 == "Dragon" || ele1 == "Steel")
            dannotot *= 0.5;
            
        if(ele2 == "Water" || ele2 == "Ground" || ele2 == "Rock")
            dannotot *= 2;
        else if(ele2 == "Fire" || ele2 == "Grass" || ele2 == "Poison" || ele2 == "Flying" || ele2 == "Bug" || ele2 == "Dragon" || ele2 == "Steel")
            dannotot *= 0.5;
        break;

        case "Ice":
        if(ele1 == "Grass" || ele1 == "Ground" || ele1 == "Flying" || ele1 == "Dragon")
            dannotot *= 2;
        else if(ele1 == "Fire" || ele1 == "Water" || ele1 == "Ice" || ele1 == "Steel")
            dannotot *= 0.5;
            
        if(ele2 == "Grass" || ele2 == "Ground" || ele2 == "Flying" || ele2 == "Dragon")
            dannotot *= 2;
        else if(ele2 == "Fire" || ele2 == "Water" || ele2 == "Ice" || ele2 == "Steel")
            dannotot *= 0.5;
        break;

        case "Psychic":
        if(ele1 == "Fighting" || ele1 == "Poison")
            dannotot *= 2;
        else if(ele1 == "Psychic" || ele1 == "Steel")
            dannotot *= 0.5;
        else if(ele1 == "Dark")
            dannotot = 0;
            
        if(ele2 == "Fighting" || ele2 == "Poison")
            dannotot *= 2;
        else if(ele2 == "Psychic" || ele2 == "Steel")
            dannotot *= 0.5;
        else if(ele2 == "Dark")
            dannotot = 0;
        break;

         case "Bug":
        if(ele1 == "Grass" || ele1 == "Psychic" || ele1 == "Dark")
            dannotot *= 2;
        else if(ele1 == "Fire" || ele1 == "Fighting" || ele1 == "Poison" || ele1 == "Flying" || ele1 == "Ghost" || ele1 == "Steel" || ele1 == "Fairy")
            dannotot *= 0.5;
            
        if(ele2 == "Grass" || ele2 == "Psychic" || ele2 == "Dark")
            dannotot *= 2;
        else if(ele2 == "Fire" || ele2 == "Fighting" || ele2 == "Poison" || ele2 == "Flying" || ele2 == "Ghost" || ele2 == "Steel" || ele2 == "Fairy")
            dannotot *= 0.5;
        break;
        
        case "Rock":
        if(ele1 == "Fire" || ele1 == "Ice" || ele1 == "Flying" || ele1 == "Bug")
            dannotot *= 2;
        else if(ele1 == "Fighting" || ele1 == "Ground" || ele1 == "Steel")
            dannotot *= 0.5;
            
        if(ele2 == "Fire" || ele2 == "Ice" || ele2 == "Flying" || ele2 == "Bug")
            dannotot *= 2;
        else if(ele2 == "Fighting" || ele2 == "Ground" || ele2 == "Steel")
            dannotot *= 0.5;
        break;
        
        case "Ghost":
        if(ele1 == "Psychic" || ele1 == "Ghost")
            dannotot *= 2;
        else if(ele1 == "Dark")
            dannotot *= 0.5;
        else if(ele1 == "Normal")
            dannotot = 0;
            
        if(ele2 == "Psychic" || ele2 == "Ghost")
            dannotot *= 2;
        else if(ele2 == "Dark")
            dannotot *= 0.5;
        else if(ele2 == "Normal")
            dannotot = 0;
        break;
        
        case "Dragon":
        if(ele1 == "Dragon")
            dannotot *= 2;
        else if(ele1 == "Steel")
            dannotot *= 0.5;
        else if(ele1 == "Fairy")
            dannotot = 0;
            
        if(ele2 == "Dragon")
            dannotot *= 2;
        else if(ele2 == "Steel")
            dannotot *= 0.5;
        else if(ele2 == "Fairy")
            dannotot = 0;
        break;
        
        case "Dark":
        if(ele1 == "Psychic" || ele1 == "Ghost")
            dannotot *= 2;
        else if(ele1 == "Fighting" || ele1 == "Dark" || ele1 == "Fairy")
            dannotot *= 0.5;
            
        if(ele2 == "Psychic" || ele2 == "Ghost")
            dannotot *= 2;
        else if(ele2 == "Fighting" || ele2 == "Dark" || ele2 == "Fairy")
            dannotot *= 0.5;
        break;
        
        case "Steel":
        if(ele1 == "Ice" || ele1 == "Rock" || ele1 == "Fairy")
            dannotot *= 2;
        else if(ele1 == "Fire" || ele1 == "Water" || ele1 == "Electric" || ele1 == "Steel")
            dannotot *= 0.5;
            
        if(ele2 == "Ice" || ele2 == "Rock" || ele2 == "Fairy")
            dannotot *= 2;
        else if(ele2 == "Fire" || ele2 == "Water" || ele2 == "Electric" || ele2 == "Steel")
            dannotot *= 0.5;
        break;
        
        case "Fairy":
        if(ele1 == "Fighting" || ele1 == "Dragon" || ele1 == "Dark")
            dannotot *= 2;
        else if(ele1 == "Fire" || ele1 == "Poison" || ele1 == "Steel")
            dannotot *= 0.5;
            
        if(ele2 == "Fighting" || ele2 == "Dragon" || ele2 == "Dark")
            dannotot *= 2;
        else if(ele2 == "Fire" || ele2 == "Poison" || ele2 == "Steel")
            dannotot *= 0.5;
        break;
    }
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
