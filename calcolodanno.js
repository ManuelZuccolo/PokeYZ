const typeEffectiveness = {
    "Normal": { strong: [], weak: ["Rock", "Steel"], immune: ["Ghost"] },
    "Fire": { strong: ["Grass", "Ice", "Bug", "Steel"], weak: ["Fire", "Water", "Rock", "Dragon"], immune: [] },
    "Water": { strong: ["Fire", "Ground", "Rock"], weak: ["Water", "Grass", "Dragon"], immune: [] },
    "Electric": { strong: ["Water", "Flying"], weak: ["Electric", "Grass", "Dragon"], immune: ["Ground"] },
    "Grass": { strong: ["Water", "Ground", "Rock"], weak: ["Fire", "Grass", "Poison", "Flying", "Bug", "Dragon", "Steel"], immune: [] },
    "Ice": { strong: ["Grass", "Ground", "Flying", "Dragon"], weak: ["Fire", "Water", "Ice", "Steel"], immune: [] },
    "Fighting": { strong: ["Normal", "Ice", "Rock", "Dark", "Steel"], weak: ["Poison", "Flying", "Psychic", "Bug", "Fairy"], immune: ["Ghost"] },
    "Poison": { strong: ["Grass", "Fairy"], weak: ["Poison", "Ground", "Rock", "Ghost"], immune: ["Steel"] },
    "Ground": { strong: ["Fire", "Electric", "Poison", "Rock", "Steel"], weak: ["Grass", "Bug"], immune: ["Flying"] },
    "Flying": { strong: ["Grass", "Fighting", "Bug"], weak: ["Electric", "Rock", "Steel"], immune: [] },
    "Psychic": { strong: ["Fighting", "Poison"], weak: ["Psychic", "Steel"], immune: ["Dark"] },
    "Bug": { strong: ["Grass", "Psychic", "Dark"], weak: ["Fire", "Fighting", "Poison", "Flying", "Ghost", "Steel", "Fairy"], immune: [] },
    "Rock": { strong: ["Fire", "Ice", "Flying", "Bug"], weak: ["Fighting", "Ground", "Steel"], immune: [] },
    "Ghost": { strong: ["Psychic", "Ghost"], weak: ["Dark"], immune: ["Normal"] },
    "Dragon": { strong: ["Dragon"], weak: ["Steel"], immune: ["Fairy"] },
    "Dark": { strong: ["Psychic", "Ghost"], weak: ["Fighting", "Dark", "Fairy"], immune: [] },
    "Steel": { strong: ["Ice", "Rock", "Fairy"], weak: ["Fire", "Water", "Electric", "Steel"], immune: [] },
    "Fairy": { strong: ["Fighting", "Dragon", "Dark"], weak: ["Fire", "Poison", "Steel"], immune: [] }
};

let accuratezza1 = 0;
let effeto1 = null;
let protetto1 = false;
let accuratezza2 = 0;
let effeto2 = null;
let protetto2 = false;

let daTogliere1 = 0;
let daTogliere2 = 0;
let daTogliere3 = 0;
let daTogliere4 = 0;
let avvelentoturni1 = 1;
let avvelentoturni2 = 1;
let aggcrt1 = 0;
let aggcrt2 = 0;
let dannotot = 0;
let turn = 1;

let atk1, def1, spa1, spd1, spe1, hp1, maxHp1, tipo1_1, tipo1_2;
let atk2, def2, spa2, spd2, spe2, hp2, maxHp2, tipo2_1, tipo2_2;
let nome1, nome2;

let aggiornaBarraHPGiocatore = null;
let aggiornaBarraHPAvversario = null;
let aggiornaMessaggio = null;

function normalizzaTipo(tipo) {
    if (!tipo || tipo === "null" || tipo === "") return null;
    if (tipo.charAt(0) === tipo.charAt(0).toUpperCase()) {
        return tipo;
    }
    return tipo.charAt(0).toUpperCase() + tipo.slice(1).toLowerCase();
}

function calcolaEfficacia(tipoAttacco, ele1, ele2 = null) {
    console.log('Calcolo efficacia:', {
        tipoAttacco: tipoAttacco,
        ele1: ele1,
        ele2: ele2
    });
    
    let moltiplicatore = 1;
    
    tipoAttacco = normalizzaTipo(tipoAttacco);
    ele1 = normalizzaTipo(ele1);
    ele2 = normalizzaTipo(ele2);
    
    if (!tipoAttacco || !typeEffectiveness[tipoAttacco]) {
        return moltiplicatore;
    }
    
    const eff = typeEffectiveness[tipoAttacco];
    
    if (ele1) {
        if (eff.strong.includes(ele1)) {
            moltiplicatore *= 2;
        } else if (eff.weak.includes(ele1)) {
            moltiplicatore *= 0.5;
        } else if (eff.immune.includes(ele1)) {
            return 0;
        }
    }
    
    if (ele2) {
        if (eff.strong.includes(ele2)) {
            moltiplicatore *= 2;
        } else if (eff.weak.includes(ele2)) {
            moltiplicatore *= 0.5;
        } else if (eff.immune.includes(ele2)) {
            return 0;
        }
    }
    
    return moltiplicatore;
}

function initCalcoloDanno(pokemon1, pokemon2, updatePlayerHP, updateEnemyHP, updateMsg) {
    atk1 = pokemon1.atk;
    def1 = pokemon1.def;
    spa1 = pokemon1.spa;
    spd1 = pokemon1.spd;
    spe1 = pokemon1.spe;
    hp1 = pokemon1.hp;
    maxHp1 = pokemon1.max_hp;
    tipo1_1 = pokemon1.tipo1;
    tipo1_2 = pokemon1.tipo2;
    nome1 = pokemon1.name;
    
    atk2 = pokemon2.atk;
    def2 = pokemon2.def;
    spa2 = pokemon2.spa;
    spd2 = pokemon2.spd;
    spe2 = pokemon2.spe;
    hp2 = pokemon2.hp;
    maxHp2 = pokemon2.max_hp;
    tipo2_1 = pokemon2.tipo1;
    tipo2_2 = pokemon2.tipo2;
    nome2 = pokemon2.name;
    
    daTogliere1 = Math.ceil(maxHp1 * 0.06);
    daTogliere2 = Math.ceil(maxHp2 * 0.06);
    daTogliere3 = Math.ceil(maxHp1 * 0.12);
    daTogliere4 = Math.ceil(maxHp2 * 0.12);
    
    effeto1 = null;
    effeto2 = null;
    avvelentoturni1 = 1;
    avvelentoturni2 = 1;
    protetto1 = false;
    protetto2 = false;
    
    aggiornaBarraHPGiocatore = updatePlayerHP;
    aggiornaBarraHPAvversario = updateEnemyHP;
    aggiornaMessaggio = updateMsg;
}

function calcolodanno(elemento, danno, isSpecial, protect, critical = false) {
    return new Promise((resolve) => {
        if (protect == true) {
            if (aggiornaMessaggio) aggiornaMessaggio("Ma " + nome2 + " si è protetto!");
            resolve(0);
            return;
        }
        
        let attack, defense;
        if (isSpecial) {
            attack = spa1;
            defense = spd2;
        } else {
            attack = atk1;
            defense = def2;
        }
        
        let level = 50;
        let baseDamage = Math.floor(Math.floor((2 * level / 5 + 2) * attack * danno / defense) / 50) + 2;
        
        let efficacia = calcolaEfficacia(elemento, tipo2_1, tipo2_2);
        
        let stab = 1;
        let elementoNorm = normalizzaTipo(elemento);
        let tipo1_1Norm = normalizzaTipo(tipo1_1);
        let tipo1_2Norm = normalizzaTipo(tipo1_2);
        
        if (elementoNorm == tipo1_1Norm || elementoNorm == tipo1_2Norm) {
            stab = 1.5;
        }
        
        let random = 0.85 + (Math.random() * 0.15);
        
        dannotot = Math.floor(baseDamage * stab * efficacia * random);
        dannotot = Math.max(1, dannotot);
        
        hp2 -= dannotot;
        hp2 = Math.max(0, Math.floor(hp2));
        
        if (aggiornaBarraHPAvversario) aggiornaBarraHPAvversario(hp2);
        
        if (aggiornaMessaggio) {
            let efficaciaMsg = "";
            if (efficacia >= 2) efficaciaMsg = "È superefficace!";
            else if (efficacia <= 0.5 && efficacia > 0) efficaciaMsg = "Non è molto efficace...";
            else if (efficacia === 0) efficaciaMsg = "Non ha effetto...";
            
            aggiornaMessaggio(nome1 + " usa " + elemento + "! " + efficaciaMsg);
        }
        
        resolve(dannotot);
    });
}

function gestisciTurno(mossa1, mossa2) {
    return new Promise((resolve) => {
        let risultato = {
            primo: null,
            secondo: null,
            hp1: hp1,
            hp2: hp2
        };
        
        if (spe1 > spe2) {
            turn = 1;
            calcolodanno(mossa1.tipo, mossa1.potenza, mossa1.isSpecial, protetto2)
                .then(danno => {
                    risultato.primo = { chi: 1, danno: danno };
                    risultato.hp2 = hp2;
                    
                    if (hp2 > 0) {
                        turn = 2;
                        return calcolodanno(mossa2.tipo, mossa2.potenza, mossa2.isSpecial, protetto1);
                    }
                    return 0;
                })
                .then(danno2 => {
                    if (danno2) {
                        risultato.secondo = { chi: 2, danno: danno2 };
                        risultato.hp1 = hp1;
                    }
                    resolve(risultato);
                });
        } else if (spe2 > spe1) {
            turn = 2;
            calcolodanno(mossa2.tipo, mossa2.potenza, mossa2.isSpecial, protetto1)
                .then(danno => {
                    risultato.primo = { chi: 2, danno: danno };
                    risultato.hp1 = hp1;
                    
                    if (hp1 > 0) {
                        turn = 1;
                        return calcolodanno(mossa1.tipo, mossa1.potenza, mossa1.isSpecial, protetto2);
                    }
                    return 0;
                })
                .then(danno2 => {
                    if (danno2) {
                        risultato.secondo = { chi: 1, danno: danno2 };
                        risultato.hp2 = hp2;
                    }
                    resolve(risultato);
                });
        } else {
            let random = Math.random() < 0.5 ? 1 : 2;
            turn = random;
            
            if (random == 1) {
                calcolodanno(mossa1.tipo, mossa1.potenza, mossa1.isSpecial, protetto2)
                    .then(danno => {
                        risultato.primo = { chi: 1, danno: danno };
                        risultato.hp2 = hp2;
                        
                        if (hp2 > 0) {
                            turn = 2;
                            return calcolodanno(mossa2.tipo, mossa2.potenza, mossa2.isSpecial, protetto1);
                        }
                        return 0;
                    })
                    .then(danno2 => {
                        if (danno2) {
                            risultato.secondo = { chi: 2, danno: danno2 };
                            risultato.hp1 = hp1;
                        }
                        resolve(risultato);
                    });
            } else {
                calcolodanno(mossa2.tipo, mossa2.potenza, mossa2.isSpecial, protetto1)
                    .then(danno => {
                        risultato.primo = { chi: 2, danno: danno };
                        risultato.hp1 = hp1;
                        
                        if (hp1 > 0) {
                            turn = 1;
                            return calcolodanno(mossa1.tipo, mossa1.potenza, mossa1.isSpecial, protetto2);
                        }
                        return 0;
                    })
                    .then(danno2 => {
                        if (danno2) {
                            risultato.secondo = { chi: 1, danno: danno2 };
                            risultato.hp2 = hp2;
                        }
                        resolve(risultato);
                    });
            }
        }
    });
}

function controllaPrecisione(accuratezza) {
    if (accuratezza === null || accuratezza >= 100) return true;
    let prob = randomInt(1, 100);
    return prob <= accuratezza;
}

async function eseguiMossa(mossa, isSpecial = false) {
    if (aggiornaMessaggio) aggiornaMessaggio(nome1 + " usa " + mossa.nome + "!");
    
    if (!controllaPrecisione(mossa.accuratezza)) {
        if (aggiornaMessaggio) aggiornaMessaggio("Il colpo di " + nome1 + " è fallito!");
        return { successo: false, danno: 0 };
    }
    
    let danno = await calcolodanno(mossa.tipo, mossa.potenza, isSpecial, protetto2);
    
    if (hp2 <= 0) {
        if (aggiornaMessaggio) aggiornaMessaggio(nome2 + " è stato sconfitto!");
    }
    
    return { successo: true, danno: danno };
}

function applicaStato(effetto, bersaglio) {
    if (bersaglio == 1) {
        effeto1 = effetto;
        if (aggiornaMessaggio) aggiornaMessaggio(nome1 + " " + ottieniMessaggioStato(effetto));
    } else {
        effeto2 = effetto;
        if (aggiornaMessaggio) aggiornaMessaggio(nome2 + " " + ottieniMessaggioStato(effetto));
    }
}

function ottieniMessaggioStato(effetto) {
    const messaggi = {
        "bruciato": "è stato bruciato!",
        "paralizzato": "è paralizzato!",
        "congelato": "è congelato!",
        "avvelenato": "è stato avvelenato!",
        "avvelenatof": "è stato gravemente avvelenato!",
        "addormentato": "si è addormentato!",
        "confuso": "è confuso!"
    };
    return messaggi[effetto] || "ha subito un effetto!";
}

function randomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

function stato() {
    if (effeto1 == "bruciato") {
        hp1 -= daTogliere1;
        atk1 = Math.floor(atk1 / 2);
        if (aggiornaBarraHPGiocatore) aggiornaBarraHPGiocatore(hp1);
        if (aggiornaMessaggio) aggiornaMessaggio(nome1 + " soffre per la bruciatura!");
    } else if (effeto2 == "bruciato") {
        hp2 -= daTogliere2;
        atk2 = Math.floor(atk2 / 2);
        if (aggiornaBarraHPAvversario) aggiornaBarraHPAvversario(hp2);
        if (aggiornaMessaggio) aggiornaMessaggio(nome2 + " soffre per la bruciatura!");
    } else if (effeto1 == "avvelenato") {
        hp1 -= daTogliere3;
        if (aggiornaBarraHPGiocatore) aggiornaBarraHPGiocatore(hp1);
        if (aggiornaMessaggio) aggiornaMessaggio(nome1 + " è danneggiato dal veleno!");
    } else if (effeto2 == "avvelenato") {
        hp2 -= daTogliere4;
        if (aggiornaBarraHPAvversario) aggiornaBarraHPAvversario(hp2);
        if (aggiornaMessaggio) aggiornaMessaggio(nome2 + " è danneggiato dal veleno!");
    } else if (effeto1 == "avvelenatof") {
        hp1 -= daTogliere3 * avvelentoturni1;
        avvelentoturni1 *= 2;
        if (aggiornaBarraHPGiocatore) aggiornaBarraHPGiocatore(hp1);
        if (aggiornaMessaggio) aggiornaMessaggio(nome1 + " è danneggiato dal veleno!");
    } else if (effeto2 == "avvelenatof") {
        hp2 -= daTogliere4 * avvelentoturni2;
        avvelentoturni2 *= 2;
        if (aggiornaBarraHPAvversario) aggiornaBarraHPAvversario(hp2);
        if (aggiornaMessaggio) aggiornaMessaggio(nome2 + " è danneggiato dal veleno!");
    }
}

window.calcoloDanno = {
    init: initCalcoloDanno,
    eseguiMossa: eseguiMossa,
    gestisciTurno: gestisciTurno,
    stato: stato,
    calcolaEfficacia: calcolaEfficacia,
    getHp: () => ({ hp1, hp2, maxHp1, maxHp2 }),
    isPokemon1Morto: () => hp1 <= 0,
    isPokemon2Morto: () => hp2 <= 0,
    getStatistiche: () => ({
        giocatore: { atk: atk1, def: def1, spa: spa1, spd: spd1, spe: spe1, hp: hp1 },
        avversario: { atk: atk2, def: def2, spa: spa2, spd: spd2, spe: spe2, hp: hp2 }
    })
};