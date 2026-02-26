// ============================================================
// TABELLA DELLE EFFICACIE DEI TIPI (DEBOLEZZE/RESISTENZE)
// ============================================================
// Ogni tipo ha:
// - strong: array di tipi contro cui è superefficace (x2)
// - weak: array di tipi contro cui è poco efficace (x0.5)
// - immune: array di tipi contro cui non ha effetto (x0)
// ============================================================
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

// ============================================================
// VARIABILI GLOBALI PER IL CALCOLO DEL DANNO
// ============================================================
// Queste variabili tengono traccia dello stato della battaglia
// e vengono aggiornate durante il combattimento
// ============================================================

// Variabili per precisione e protezione
let accuratezza1 = 0;
let effeto1 = null;
let protetto1 = false;
let accuratezza2 = 0;
let effeto2 = null;
let protetto2 = false;

// Danni da status (calcolati in percentuale dell'HP massimo)
let daTogliere1 = 0;       // Danno da bruciatura al giocatore (6% HP)
let daTogliere2 = 0;       // Danno da bruciatura all'avversario (6% HP)
let daTogliere3 = 0;       // Danno da veleno al giocatore (12% HP)
let daTogliere4 = 0;       // Danno da veleno all'avversario (12% HP)
let avvelentoturni1 = 1;   // Moltiplicatore per veleno grave (raddoppia ogni turno)
let avvelentoturni2 = 1;   // Moltiplicatore per veleno grave (raddoppia ogni turno)
let aggcrt1 = 0;           // Boost al tasso critico del giocatore
let aggcrt2 = 0;           // Boost al tasso critico dell'avversario
let dannotot = 0;          // Variabile temporanea per il danno calcolato
let turn = 1;              // Indicatore di chi sta attaccando (1 = giocatore, 2 = avversario)

// ============================================================
// STATISTICHE DEI POKEMON IN BATTAGLIA
// ============================================================
// Queste variabili vengono inizializzate dalla funzione initCalcoloDanno()
// e contengono le statistiche attuali dei due Pokémon in campo
// ============================================================
let atk1, def1, spa1, spd1, spe1, hp1, maxHp1, tipo1_1, tipo1_2;  // Statistiche giocatore
let atk2, def2, spa2, spd2, spe2, hp2, maxHp2, tipo2_1, tipo2_2;  // Statistiche avversario
let nome1, nome2;  // Nomi dei Pokémon (per i messaggi)

// ============================================================
// CALLBACK PER L'AGGIORNAMENTO DELL'INTERFACCIA
// ============================================================
// Queste funzioni vengono passate da battaglia.js e permettono
// di aggiornare l'interfaccia utente quando cambiano gli HP
// ============================================================
let aggiornaBarraHPGiocatore = null;
let aggiornaBarraHPAvversario = null;
let aggiornaMessaggio = null;

// ============================================================
// FUNZIONE: calcolaEfficacia
// SCOPO: Calcola il moltiplicatore di danno in base ai tipi
// PARAMETRI:
//   - tipoAttacco: il tipo della mossa usata
//   - ele1: primo tipo del Pokémon bersaglio
//   - ele2: secondo tipo del Pokémon bersaglio (opzionale)
// RETURN: moltiplicatore (0, 0.5, 1, 2, 4, ecc.)
// ============================================================
function calcolaEfficacia(tipoAttacco, ele1, ele2 = null) {
    let moltiplicatore = 1;
    
    // Se il tipo non è nella tabella, restituisci 1
    if (!typeEffectiveness[tipoAttacco]) return moltiplicatore;
    
    const eff = typeEffectiveness[tipoAttacco];
    
    // Capitalizza i tipi per il confronto
    if (ele1) ele1 = ele1.charAt(0).toUpperCase() + ele1.slice(1).toLowerCase();
    if (ele2) ele2 = ele2.charAt(0).toUpperCase() + ele2.slice(1).toLowerCase();
    
    // Controlla il primo tipo del bersaglio
    if (eff.strong.includes(ele1)) moltiplicatore *= 2;
    else if (eff.weak.includes(ele1)) moltiplicatore *= 0.5;
    else if (eff.immune.includes(ele1)) return 0;
    
    // Controlla il secondo tipo del bersaglio (se presente)
    if (ele2) {
        if (eff.strong.includes(ele2)) moltiplicatore *= 2;
        else if (eff.weak.includes(ele2)) moltiplicatore *= 0.5;
        else if (eff.immune.includes(ele2)) return 0;
    }
    
    return moltiplicatore;
}

// ============================================================
// FUNZIONE: initCalcoloDanno
// SCOPO: Inizializza le variabili di battaglia con i dati dei Pokémon
// PARAMETRI:
//   - pokemon1: oggetto con le statistiche del giocatore
//   - pokemon2: oggetto con le statistiche dell'avversario
//   - updatePlayerHP: callback per aggiornare HP giocatore
//   - updateEnemyHP: callback per aggiornare HP avversario
//   - updateMsg: callback per mostrare messaggi
// ============================================================
function initCalcoloDanno(pokemon1, pokemon2, updatePlayerHP, updateEnemyHP, updateMsg) {
    console.log('📊 Inizializzazione calcolo danno:', pokemon1.name, 'vs', pokemon2.name);
    
    // ===== STATISTICHE POKEMON 1 (GIOCATORE) =====
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
    
    // ===== STATISTICHE POKEMON 2 (AVVERSARIO) =====
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
    
    // ===== CALCOLO DANNI DA STATUS =====
    daTogliere1 = Math.ceil(maxHp1 * 0.06);
    daTogliere2 = Math.ceil(maxHp2 * 0.06);
    daTogliere3 = Math.ceil(maxHp1 * 0.12);
    daTogliere4 = Math.ceil(maxHp2 * 0.12);
    
    // ===== RESET EFFETTI STATO (opzionale - commenta se vuoi mantenerli) =====
    effeto1 = null;
    effeto2 = null;
    avvelentoturni1 = 1;
    avvelentoturni2 = 1;
    protetto1 = false;
    protetto2 = false;
    
    // ===== CALLBACK =====
    aggiornaBarraHPGiocatore = updatePlayerHP;
    aggiornaBarraHPAvversario = updateEnemyHP;
    aggiornaMessaggio = updateMsg;
    
    console.log('✅ Statistiche aggiornate:', {
        giocatore: { 
            nome: nome1,
            atk: atk1, 
            def: def1, 
            spa: spa1, 
            spd: spd1, 
            spe: spe1, 
            hp: hp1,
            maxHp: maxHp1,
            tipo1: tipo1_1,
            tipo2: tipo1_2
        },
        avversario: { 
            nome: nome2,
            atk: atk2, 
            def: def2, 
            spa: spa2, 
            spd: spd2, 
            spe: spe2, 
            hp: hp2,
            maxHp: maxHp2,
            tipo1: tipo2_1,
            tipo2: tipo2_2
        }
    });
}

// ============================================================
// FUNZIONE: calcolodanno
// SCOPO: Calcola il danno di una mossa e lo applica al bersaglio
// PARAMETRI:
//   - elemento: tipo della mossa
//   - danno: potenza base della mossa
//   - isSpecial: true se la mossa è speciale, false se fisica
//   - protect: true se il bersaglio è protetto
//   - critical: true se è un colpo critico (non ancora implementato)
// RETURN: Promise con il danno inflitto
// ============================================================
function calcolodanno(elemento, danno, isSpecial, protect, critical = false) {
    return new Promise((resolve) => {
        // ===== CONTROLLO PROTEZIONE =====
        if (protect == true) {
            if (aggiornaMessaggio) aggiornaMessaggio("Ma " + nome2 + " si è protetto!");
            resolve(0);
            return;
        }
        
        // ===== DETERMINAZIONE ATTACCO/DIFESA =====
        let attack, defense;
        if (isSpecial) {
            attack = spa1;
            defense = spd2;
        } else {
            attack = atk1;
            defense = def2;
        }
        
        // ===== FORMULA BASE DEL DANNO (Generazioni 3-5) =====
        let level = 50;
        let baseDamage = Math.floor(Math.floor((2 * level / 5 + 2) * attack * danno / defense) / 50) + 2;
        
        // ===== CALCOLO MOLTIPLICATORI =====
        let efficacia = calcolaEfficacia(elemento, tipo2_1, tipo2_2);
        
        // STAB (Same Type Attack Bonus)
        let stab = 1;
        if (elemento.toLowerCase() == tipo1_1?.toLowerCase() || elemento.toLowerCase() == tipo1_2?.toLowerCase()) {
            stab = 1.5;
        }
        
        // Variazione casuale (85-100%)
        let random = 0.85 + (Math.random() * 0.15);
        
        // ===== CALCOLO DANNO TOTALE =====
        dannotot = Math.floor(baseDamage * stab * efficacia * random);
        dannotot = Math.max(1, dannotot); // Almeno 1 danno
        
        // ===== APPLICAZIONE DANNO =====
        hp2 -= dannotot;
        hp2 = Math.max(0, Math.floor(hp2));
        
        if (aggiornaBarraHPAvversario) aggiornaBarraHPAvversario(hp2);
        
        // ===== MESSAGGIO DI EFFICACIA =====
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

// ============================================================
// FUNZIONE: gestisciTurno
// SCOPO: Gestisce l'ordine di attacco in base alla velocità
// ============================================================
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

// ============================================================
// FUNZIONE: controllaPrecisione
// SCOPO: Verifica se un attacco va a segno
// ============================================================
function controllaPrecisione(accuratezza) {
    if (accuratezza === null || accuratezza >= 100) return true;
    let prob = randomInt(1, 100);
    return prob <= accuratezza;
}

// ============================================================
// FUNZIONE: eseguiMossa
// SCOPO: Funzione principale chiamata da battaglia.js
// ============================================================
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

// ============================================================
// FUNZIONE: applicaStato
// SCOPO: Applica un effetto di stato a un Pokémon
// ============================================================
function applicaStato(effetto, bersaglio) {
    if (bersaglio == 1) {
        effeto1 = effetto;
        if (aggiornaMessaggio) aggiornaMessaggio(nome1 + " " + ottieniMessaggioStato(effetto));
    } else {
        effeto2 = effetto;
        if (aggiornaMessaggio) aggiornaMessaggio(nome2 + " " + ottieniMessaggioStato(effetto));
    }
}

// ============================================================
// FUNZIONE: ottieniMessaggioStato
// SCOPO: Restituisce il messaggio per un effetto di stato
// ============================================================
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

// ============================================================
// FUNZIONE: randomInt
// SCOPO: Genera un numero intero casuale
// ============================================================
function randomInt(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// ============================================================
// FUNZIONE: stato
// SCOPO: Gestisce i danni da stato alla fine di ogni turno
// ============================================================
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

// ============================================================
// ESPORTAZIONE DELLE FUNZIONI
// ============================================================
window.calcoloDanno = {
    init: initCalcoloDanno,
    eseguiMossa: eseguiMossa,
    gestisciTurno: gestisciTurno,
    stato: stato,
    calcolaEfficacia: calcolaEfficacia,
    getHp: () => ({ hp1, hp2, maxHp1, maxHp2 }),
    isPokemon1Morto: () => hp1 <= 0,
    isPokemon2Morto: () => hp2 <= 0,
    // Funzioni di utilità per debug
    getStatistiche: () => ({
        giocatore: { atk: atk1, def: def1, spa: spa1, spd: spd1, spe: spe1, hp: hp1 },
        avversario: { atk: atk2, def: def2, spa: spa2, spd: spd2, spe: spe2, hp: hp2 }
    })
};