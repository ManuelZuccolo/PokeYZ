// Tabella delle efficacie dei tipi
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

// Variabili globali per il calcolo del danno
let accuratezza1 = 0;
let effeto1;
let protetto1 = false;
let accuratezza2 = 0;
let effeto2;
let protetto2 = false;

// Danni da status
let daTogliere1 = 0; // Bruciatura giocatore
let daTogliere2 = 0; // Bruciatura avversario
let daTogliere3 = 0; // Veleno giocatore
let daTogliere4 = 0; // Veleno avversario
let avvelentoturni1 = 1;
let avvelentoturni2 = 1;
let aggcrt1 = 0;
let aggcrt2 = 0;
let dannotot = 0;
let turn = 1;

// Variabili per i Pokémon in battaglia
let atk1, def1, spa1, spd1, spe1, hp1, maxHp1, tipo1_1, tipo1_2;
let atk2, def2, spa2, spd2, spe2, hp2, maxHp2, tipo2_1, tipo2_2;
let nome1, nome2;

// Riferimenti alle funzioni di aggiornamento UI
let aggiornaBarraHPGiocatore = null;
let aggiornaBarraHPAvversario = null;
let aggiornaMessaggio = null;

// Funzione per calcolare l'efficacia (NUOVA VERSIONE OTTIMIZZATA)
function calcolaEfficacia(tipoAttacco, ele1, ele2 = null) {
    let moltiplicatore = 1;
    
    if (!typeEffectiveness[tipoAttacco]) return moltiplicatore;
    
    const eff = typeEffectiveness[tipoAttacco];
    
    // Capitalizza i tipi per il confronto
    if (ele1) ele1 = ele1.charAt(0).toUpperCase() + ele1.slice(1).toLowerCase();
    if (ele2) ele2 = ele2.charAt(0).toUpperCase() + ele2.slice(1).toLowerCase();
    
    // Controlla primo tipo
    if (eff.strong.includes(ele1)) moltiplicatore *= 2;
    else if (eff.weak.includes(ele1)) moltiplicatore *= 0.5;
    else if (eff.immune.includes(ele1)) return 0;
    
    // Controlla secondo tipo (se presente)
    if (ele2) {
        if (eff.strong.includes(ele2)) moltiplicatore *= 2;
        else if (eff.weak.includes(ele2)) moltiplicatore *= 0.5;
        else if (eff.immune.includes(ele2)) return 0;
    }
    
    return moltiplicatore;
}

// Funzione per inizializzare le variabili di battaglia
function initCalcoloDanno(pokemon1, pokemon2, updatePlayerHP, updateEnemyHP, updateMsg) {
    console.log('Inizializzazione calcolo danno:', pokemon1, pokemon2);
    
    // Pokémon giocatore
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
    
    // Pokémon avversario
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
    
    // Calcolo danni da status (6% e 12% dell'HP max)
    daTogliere1 = Math.ceil(maxHp1 * 0.06);
    daTogliere2 = Math.ceil(maxHp2 * 0.06);
    daTogliere3 = Math.ceil(maxHp1 * 0.12);
    daTogliere4 = Math.ceil(maxHp2 * 0.12);
    
    // Funzioni di callback per aggiornare l'interfaccia
    aggiornaBarraHPGiocatore = updatePlayerHP;
    aggiornaBarraHPAvversario = updateEnemyHP;
    aggiornaMessaggio = updateMsg;
    
    console.log('Calcolo danno inizializzato:', {
        hp1, maxHp1, hp2, maxHp2,
        daTogliere1, daTogliere2, daTogliere3, daTogliere4
    });
}

// Funzione principale per il calcolo del danno (USA LA NUOVA funzione di efficacia)
function calcolodanno(elemento, danno, isSpecial, protect, critical = false) {
    return new Promise((resolve) => {
        if (protect == true) {
            if (aggiornaMessaggio) aggiornaMessaggio("Ma " + nome2 + " si è protetto!");
            resolve(0);
            return;
        }
        
        // Determina se usare Attacco/Difesa fisici o speciali
        let attack, defense;
        if (isSpecial) {
            attack = spa1;
            defense = spd2;
        } else {
            attack = atk1;
            defense = def2;
        }
        
        // Formula base del danno (Gen 3-5)
        let level = 50; // Livello fisso per ora
        let baseDamage = Math.floor(Math.floor((2 * level / 5 + 2) * attack * danno / defense) / 50) + 2;
        
        // Calcola il moltiplicatore di efficacia usando la nuova funzione
        let efficacia = calcolaEfficacia(elemento, tipo2_1, tipo2_2);
        
        // STAB (Same Type Attack Bonus)
        let stab = 1;
        if (elemento.toLowerCase() == tipo1_1?.toLowerCase() || elemento.toLowerCase() == tipo1_2?.toLowerCase()) {
            stab = 1.5;
        }
        
        // Variazione casuale (85-100%)
        let random = 0.85 + (Math.random() * 0.15);
        
        // Calcola danno totale con tutti i moltiplicatori
        dannotot = Math.floor(baseDamage * stab * efficacia * random);
        
        // Assicura almeno 1 danno
        dannotot = Math.max(1, dannotot);
        
        // Applica danno
        hp2 -= dannotot;
        hp2 = Math.max(0, Math.floor(hp2));
        
        // Aggiorna barra HP
        if (aggiornaBarraHPAvversario) aggiornaBarraHPAvversario(hp2);
        
        // Messaggio di efficacia
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

// Funzione per gestire il turno in base alla velocità
function gestisciTurno(mossa1, mossa2) {
    return new Promise((resolve) => {
        let risultato = {
            primo: null,
            secondo: null,
            hp1: hp1,
            hp2: hp2
        };
        
        if (spe1 > spe2) {
            // Giocatore attacca prima
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
            // Avversario attacca prima
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
            // Stessa velocità - casuale
            let random = Math.random() < 0.5 ? 1 : 2;
            turn = random;
            
            if (random == 1) {
                // Giocatore attacca prima
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
                // Avversario attacca prima
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

// Funzione per controllare se il colpo va a segno
function controllaPrecisione(accuratezza) {
    if (accuratezza === null || accuratezza >= 100) return true;
    let prob = randomInt(1, 100);
    return prob <= accuratezza;
}

// Funzione per eseguire una mossa (chiamata da battaglia.js)
async function eseguiMossa(mossa, isSpecial = false) {
    if (aggiornaMessaggio) aggiornaMessaggio(nome1 + " usa " + mossa.nome + "!");
    
    // Controlla precisione
    if (!controllaPrecisione(mossa.accuratezza)) {
        if (aggiornaMessaggio) aggiornaMessaggio("Il colpo di " + nome1 + " è fallito!");
        return { successo: false, danno: 0 };
    }
    
    // Calcola danno
    let danno = await calcolodanno(mossa.tipo, mossa.potenza, isSpecial, protetto2);
    
    // Controlla se il Pokémon avversario è morto
    if (hp2 <= 0) {
        if (aggiornaMessaggio) aggiornaMessaggio(nome2 + " è stato sconfitto!");
    }
    
    return { successo: true, danno: danno };
}

// Funzione per applicare effetti di stato
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

// Esporta le funzioni per l'uso in battaglia.js
window.calcoloDanno = {
    init: initCalcoloDanno,
    eseguiMossa: eseguiMossa,
    gestisciTurno: gestisciTurno,
    stato: stato,
    calcolaEfficacia: calcolaEfficacia,
    getHp: () => ({ hp1, hp2, maxHp1, maxHp2 }),
    isPokemon1Morto: () => hp1 <= 0,
    isPokemon2Morto: () => hp2 <= 0
};