-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Feb 24, 2026 alle 13:20
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pokeyz_db`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `abilita`
--

CREATE TABLE `abilita` (
  `id_abilita` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `effetto` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `abilita`
--

INSERT INTO `abilita` (`id_abilita`, `nome`, `effetto`) VALUES
(1, 'Blaze', 'up Fire-type moves when the Pokémon\'s HP is low.'),
(2, 'Multiscale', 'Reduces the amount of damage the Pokémon takes while its HP is full.'),
(3, 'Stench', 'The stench may cause the target to flinch.'),
(4, 'Drizzle', 'The Pokémon makes it rain when it enters a battle.'),
(5, 'Speed Boost', 'The Pokémon\'s Speed stat is boosted every turn.'),
(6, 'Battle Armor', 'Protects the Pokémon from critical hits.'),
(7, 'Sturdy', 'The Pokémon cannot be knocked out by a single hit as long as its HP is full. One-hit KO moves will also fail to knock it out.'),
(8, 'Damp', 'Prevents the use of explosive moves such as Self-Destruct by dampening its surroundings.'),
(9, 'Limber', 'The Pokémon is protected from paralysis.'),
(10, 'Sand Veil', 'Boosts the Pokémon\'s evasiveness in a sandstorm.'),
(11, 'Static', 'Contact with the Pokémon may cause paralysis.'),
(12, 'Volt Absorb', 'Restores HP if hit by an Electric-type move instead of taking damage.'),
(13, 'Water Absorb', 'Restores HP if hit by a Water-type move instead of taking damage.'),
(14, 'Oblivious', 'The Pokémon is oblivious, keeping it from being infatuated or falling for taunts.'),
(15, 'Cloud Nine', 'Eliminates the effects of weather.'),
(16, 'Compound Eyes', 'The Pokémon\'s compound eyes boost its accuracy.'),
(17, 'Insomnia', 'The Pokémon is suffering from insomnia and cannot fall asleep.'),
(18, 'Color Change', 'The Pokémon\'s type becomes the type of the move used on it.'),
(19, 'Immunity', 'The immune system of the Pokémon prevents it from getting poisoned.'),
(20, 'Flash Fire', 'If hit by a Fire-type move, the Pokémon absorbs the flames and uses them to power up its own Fire-type moves.'),
(21, 'Shield Dust', 'This Pokémon\'s dust blocks the additional effects of attacks taken.'),
(22, 'Own Tempo', 'This Pokémon has its own tempo, and that prevents it from becoming confused or being affected by Intimidate.'),
(23, 'Suction Cups', 'This Pokémon uses suction cups to stay in one spot to negate all moves that force switching out.'),
(24, 'Intimidate', 'The Pokémon intimidates opposing Pokémon upon entering battle, lowering their Attack stat.'),
(25, 'Shadow Tag', 'This Pokémon steps on the opposing Pokémon\'s shadow to prevent it from escaping.'),
(26, 'Rough Skin', 'This Pokémon inflicts damage with its rough skin to the attacker on contact.'),
(27, 'Wonder Guard', 'Its mysterious power only lets supereffective moves hit the Pokémon.'),
(28, 'Levitate', 'By floating in the air, the Pokémon receives full immunity to all Ground-type moves.'),
(29, 'Effect Spore', 'Contact with the Pokémon may inflict poison, sleep, or paralysis on its attacker.'),
(30, 'Synchronize', 'If the Pokémon is burned, paralyzed, or poisoned by another Pokémon, that Pokémon will be inflicted with the same status condition.'),
(31, 'Clear Body', 'Prevents other Pokémon\'s moves or Abilities from lowering the Pokémon\'s stats.'),
(32, 'Natural Cure', 'All status conditions heal when the Pokémon switches out.'),
(33, 'Lightning Rod', 'The Pokémon draws in all Electric-type moves. Instead of taking damage from them, its Sp. Atk stat is boosted.'),
(34, 'Serene Grace', 'Raises the likelihood of additional effects occurring when the Pokémon uses its moves.'),
(35, 'Swift Swim', 'Boosts the Pokémon\'s Speed stat in rain.'),
(36, 'Chlorophyll', 'Boosts the Pokémon\'s Speed stat in harsh sunlight.'),
(37, 'Illuminate', 'Raises the likelihood of meeting wild Pokémon by illuminating the surroundings.'),
(38, 'Trace', 'When it enters a battle, the Pokémon copies an opposing Pokémon\'s Ability.'),
(39, 'Huge Power', 'Doubles the Pokémon\'s Attack stat.'),
(40, 'Poison Point', 'Contact with the Pokémon may poison the attacker.'),
(41, 'Inner Focus', 'The Pokémon\'s intense focus prevents it from flinching or being affected by Intimidate.'),
(42, 'Magma Armor', 'The Pokémon is covered with hot magma, which prevents the Pokémon from becoming frozen.'),
(43, 'Water Veil', 'The Pokémon is covered with a water veil, which prevents the Pokémon from getting a burn.'),
(44, 'Magnet Pull', 'Prevents Steel-type Pokémon from escaping using its magnetic force.'),
(45, 'Soundproof', 'Soundproofing of the Pokémon itself gives full immunity to all sound-based moves.'),
(46, 'Rain Dish', 'The Pokémon gradually regains HP in rain.'),
(47, 'Sand Stream', 'The Pokémon summons a sandstorm when it enters a battle.'),
(48, 'Pressure', 'The Pokémon puts pressure on the opposing Pokémon, causing them to use more PP.'),
(49, 'Thick Fat', 'The Pokémon is protected by a layer of thick fat, which halves the damage taken from Fire- and Ice-type moves.'),
(50, 'Early Bird', 'The Pokémon awakens from sleep twice as fast as other Pokémon.'),
(51, 'Flame Body', 'Contact with the Pokémon may burn the attacker.'),
(52, 'Run Away', 'Enables a sure getaway from wild Pokémon.'),
(53, 'Keen Eye', 'Keen eyes prevent other Pokémon from lowering this Pokémon\'s accuracy.'),
(54, 'Hyper Cutter', 'The Pokémon\'s proud of its powerful pincers. They prevent other Pokémon from lowering its Attack stat.'),
(55, 'Pickup', 'The Pokémon may pick up an item another Pokémon used during a battle. It may pick up items outside of battle, too.'),
(56, 'Truant', 'Each time the Pokémon uses a move, it spends the next turn loafing around.'),
(57, 'Hustle', 'Boosts the Attack stat, but lowers accuracy.'),
(58, 'Cute Charm', 'Contact with the Pokémon may cause infatuation.'),
(59, 'Plus', 'Boosts the Sp. Atk stat of the Pokémon if an ally with the Plus or Minus Ability is also in battle.'),
(60, 'Minus', 'Boosts the Sp. Atk stat of the Pokémon if an ally with the Plus or Minus Ability is also in battle.'),
(61, 'Forecast', 'Castform transforms with the weather to change its type to Water, Fire, or Ice.'),
(62, 'Sticky Hold', 'Protects the Pokémon from item theft.'),
(63, 'Shed Skin', 'The Pokémon may heal its own status conditions by shedding its skin.'),
(64, 'Guts', 'It\'s so gutsy that having a status condition boosts the Pokémon\'s Attack stat.'),
(65, 'Marvel Scale', 'The Pokémon\'s marvelous scales boost the Defense stat if it has a status condition.'),
(66, 'Liquid Ooze', 'The oozed liquid has a strong stench, which damages attackers using any draining move.'),
(67, 'Overgrow', 'Powers up Grass-type moves when the Pokémon\'s HP is low.'),
(68, 'Torrent', 'Powers up Water-type moves when the Pokémon\'s HP is low.'),
(69, 'Swarm', 'Powers up Bug-type moves when the Pokémon\'s HP is low.'),
(70, 'Rock Head', 'Protects the Pokémon from recoil damage.'),
(71, 'Drought', 'Turns the sunlight harsh when the Pokémon enters a battle.'),
(72, 'Arena Trap', 'Prevents opposing Pokémon from fleeing.'),
(73, 'Vital Spirit', 'The Pokémon is full of vitality, and that prevents it from falling asleep.'),
(74, 'White Smoke', 'The is protected by its white smoke, which prevents other Pokémon from lowering its stats.'),
(75, 'Pure Power', 'Using its pure power, the Pokémon doubles its Attack stat.'),
(76, 'Shell Armor', 'A hard shell protects the Pokémon from critical hits.'),
(77, 'Air Lock', 'Eliminates the effects of weather.'),
(78, 'Tangled Feet', 'Boosts evasiveness if the Pokémon is confused.'),
(79, 'Motor Drive', 'The Pokémon takes no damage when hit by Electric-type moves. Instead, Speed stat is boosted.'),
(80, 'Rivalry', 'Becomes competitive and deals more damage to Pokémon of the same gender, but deals less to opposite gender.'),
(81, 'Steadfast', 'The Pokémon\'s determination boosts its Speed stat every time it flinches.'),
(82, 'Snow Cloak', 'Boosts evasiveness in a hailstorm.'),
(83, 'Gluttony', 'If the Pokémon is holding a Berry to be eaten when its HP is low, it will instead eat the Berry when its HP drops to half or less.'),
(84, 'Anger Point', 'The Pokémon gets angry when it takes a critical hit, and that maxes its Attack stat.'),
(85, 'Unburden', 'Boosts the Speed stat if the Pokémon loses its held item.'),
(86, 'Heatproof', 'The heatproof body of the Pokémon halves the damage from Fire-type moves.'),
(87, 'Simple', 'The Pokémon doubles the effect of stat changes.'),
(88, 'Dry Skin', 'Restores HP in rain or when hit by Water-type moves. Reduces HP in harsh sunlight, and increases the damage from Fire-type moves.'),
(89, 'Download', 'Compares an opponent\'s Defense and Sp. Def stats to raise its own Attack or Sp. Atk stat accordingly.'),
(90, 'Iron Fist', 'Powers up punching moves.'),
(91, 'Poison Heal', 'Restores HP if the Pokémon is poisoned instead of losing HP.'),
(92, 'Adaptability', 'Powers up moves of the same type as the Pokémon.'),
(93, 'Skill Link', 'Enables multi-strike moves that hit 2-5 times per use to always hit the maximum number of times.'),
(94, 'Hydration', 'Heals status conditions in rain.'),
(95, 'Solar Power', 'Boosts Sp. Atk stat in harsh sunlight, but HP decreases.'),
(96, 'Quick Feet', 'Boosts the Speed stat if the Pokémon has a status condition.'),
(97, 'Normalize', 'All the Pokémon\'s moves become Normal type. The power of those moves is boosted a little.'),
(98, 'Sniper', 'Powers up moves if they become critical hits.'),
(99, 'Magic Guard', 'The Pokémon only takes damage from attacks.'),
(100, 'No Guard', 'Ensures all attacks by the Pokémon and against the Pokémon land.'),
(101, 'Stall', 'The Pokémon will always move last among Pokémon with the same priority level.'),
(102, 'Technician', 'Powers up the Pokémon\'s weaker moves.'),
(103, 'Leaf Guard', 'Prevents status conditions in harsh sunlight.'),
(104, 'Klutz', 'The Pokémon cannot use any held items.'),
(105, 'Mold Breaker', 'Moves can be used on the target regardless of its Abilities.'),
(106, 'Super Luck', 'The Pokémon is so lucky that the critical-hit ratios of its moves are boosted.'),
(107, 'Aftermath', 'Damages the attacker if it contacts the Pokémon with a finishing hit.'),
(108, 'Anticipation', 'The Pokémon can sense an opposing Pokémon\'s dangerous moves.'),
(109, 'Forewarn', 'When it enters a battle, the Pokémon can tell one of the moves an opposing Pokémon has.'),
(110, 'Unaware', 'When attacking, the Pokémon ignores the target Pokémon\'s stat changes.'),
(111, 'Tinted Lens', 'Powers up \"not very effective\" moves.'),
(112, 'Filter', 'Reduces the power of supereffective attacks taken.'),
(113, 'Slow Start', 'For five turns, the Pokémon\'s Attack and Speed stats are halved.'),
(114, 'Scrappy', 'Enables the Pokémon to hit Ghost-type Pokémon with Normal- and Fighting-type moves.'),
(115, 'Storm Drain', 'Draws in all Water-type moves. Instead of taking damage from them, its Sp. Atk is boosted.'),
(116, 'Ice Body', 'The Pokémon gradually regains HP in a hailstorm.'),
(117, 'Solid Rock', 'Reduces the power of supereffective attacks taken.'),
(118, 'Snow Warning', 'The Pokémon summons a hailstorm when it enters a battle.'),
(119, 'Honey Gather', 'The Pokémon may gather Honey after a battle.'),
(120, 'Frisk', 'When it enters a battle, the Pokémon can check an opposing Pokémon\'s held item.'),
(121, 'Reckless', 'Powers up moves that have recoil damage.'),
(122, 'Multitype', 'Changes the Pokémon\'s type to match the Plate or Z-Crystal it holds.'),
(123, 'Flower Gift', 'Boosts the Attack and Sp. Def stats of itself and allies in harsh sunlight.'),
(124, 'Bad Dreams', 'Reduces the HP of sleeping opposing Pokémon.'),
(125, 'Pickpocket', 'Steals an item from an attacker that made direct contact.'),
(126, 'Sheer Force', 'Removes additional effects to increase the power of moves when attacking.'),
(127, 'Contrary', 'Makes stat changes have an opposite effect.'),
(128, 'Unnerve', 'Unnerves opposing Pokémon and makes them unable to eat Berries.'),
(129, 'Defiant', 'Boosts the Pokémon\'s Attack stat sharply when its stats are lowered.'),
(130, 'Defeatist', 'Halves the Attack and Sp. Atk stats when HP becomes half or less.'),
(131, 'Cursed Body', 'May disable a move that has dealt damage to the Pokémon.'),
(132, 'Healer', 'Sometimes cures the status conditions of the Pokémon’s allies.'),
(133, 'Friend Guard', 'Reduces damage dealt to allies.'),
(134, 'Weak Armor', 'The Pokémon’s Defense stat is lowered when it takes damage from physical moves, but its Speed stat is sharply boosted.'),
(135, 'Heavy Metal', 'Doubles the Pokémon’s weight.'),
(136, 'Light Metal', 'Halves the Pokémon’s weight.'),
(137, 'Toxic Boost', 'Powers up physical moves when the Pokémon is poisoned.'),
(138, 'Flare Boost', 'Powers up special moves when the Pokémon is burned.'),
(139, 'Harvest', 'May create another Berry after one is used.'),
(140, 'Telepathy', 'The Pokémon anticipates and dodges the attacks of its allies.'),
(141, 'Moody', 'Every turn, one of the Pokémon’s stats will be boosted sharply but another stat will be lowered.'),
(142, 'Overcoat', 'The Pokémon takes no damage from sandstorms. It is also protected from the effects of powders and spores.'),
(143, 'Poison Touch', 'May poison a target when the Pokémon makes contact.'),
(144, 'Regenerator', 'The Pokémon has a little of its HP restored when withdrawn from battle.'),
(145, 'Big Pecks', 'Prevents the Pokémon from having its Defense stat lowered.'),
(146, 'Sand Rush', 'Boosts the Pokémon’s Speed stat in a sandstorm.'),
(147, 'Wonder Skin', 'Makes status moves more likely to miss the Pokémon.'),
(148, 'Analytic', 'Boosts the power of the Pokémon’s move if it is the last to act that turn.'),
(149, 'Illusion', 'The Pokémon fools opponents by entering battle disguised as the last Pokémon in its Trainer’s party.'),
(150, 'Imposter', 'The Pokémon transforms itself into the Pokémon it’s facing.'),
(151, 'Infiltrator', 'The Pokémon’s moves are unaffected by the target’s barriers, substitutes, and the like.'),
(152, 'Mummy', 'Contact with the Pokémon changes the attacker’s Ability to Mummy.'),
(153, 'Moxie', 'When the Pokémon knocks out a target, it shows moxie, which boosts its Attack stat.'),
(154, 'Justified', 'When the Pokémon is hit by a Dark-type attack, its Attack stat is boosted by its sense of justice.'),
(155, 'Rattled', 'The Pokémon gets scared when hit by a Dark-, Ghost-, or Bug-type attack or if intimidated, which boosts its Speed stat.'),
(156, 'Magic Bounce', 'The Pokémon reflects status moves instead of getting hit by them.'),
(157, 'Sap Sipper', 'The Pokémon takes no damage when hit by Grass-type moves. Instead, its Attack stat is boosted.'),
(158, 'Prankster', 'Gives priority to the Pokémon’s status moves.'),
(159, 'Sand Force', 'Boosts the power of Rock-, Ground-, and Steel-type moves in a sandstorm.'),
(160, 'Iron Barbs', 'The Pokémon’s iron barbs damage the attacker if it makes direct contact.'),
(161, 'Zen Mode', 'Changes the Pokémon’s shape when its HP drops to half or less.'),
(162, 'Victory Star', 'Boosts the accuracy of the Pokémon and its allies.'),
(163, 'Turboblaze', 'The Pokémon’s moves are unimpeded by the Ability of the target.'),
(164, 'Teravolt', 'The Pokémon’s moves are unimpeded by the Ability of the target.'),
(165, 'Aroma Veil', 'Protects the Pokémon and its allies from effects that prevent the use of moves.'),
(166, 'Flower Veil', 'Ally Grass-type Pokémon are protected from status conditions and the lowering of their stats.'),
(167, 'Cheek Pouch', 'The Pokémon’s HP is restored when it eats any Berry, in addition to the Berry’s usual effect.'),
(168, 'Protean', 'Changes the Pokémon’s type to the type of the move it’s about to use. This works only once each time the Pokémon enters battle.'),
(169, 'Fur Coat', 'Halves the damage from physical moves.'),
(170, 'Magician', 'The Pokémon steals the held item from any target it hits with a move.'),
(171, 'Bulletproof', 'Protects the Pokémon from ball and bomb moves.'),
(172, 'Competitive', 'Boosts the Pokémon’s Sp. Atk stat sharply when its stats are lowered by an opposing Pokémon.'),
(173, 'Strong Jaw', 'The Pokémon’s strong jaw boosts the power of its biting moves.'),
(174, 'Refrigerate', 'Normal-type moves become Ice-type moves. The power of those moves is boosted a little.'),
(175, 'Sweet Veil', 'Prevents the Pokémon and its allies from falling asleep.'),
(176, 'Stance Change', 'The Pokémon changes its form to Blade Forme when it uses an attack move and changes to Shield Forme when it uses King’s Shield.'),
(177, 'Gale Wings', 'Gives priority to the Pokémon’s Flying-type moves while its HP is full.'),
(178, 'Mega Launcher', 'Powers up pulse moves.'),
(179, 'Grass Pelt', 'Boosts the Pokémon’s Defense stat on Grassy Terrain.'),
(180, 'Symbiosis', 'The Pokémon passes its held item to an ally that has used up an item.'),
(181, 'Tough Claws', 'Powers up moves that make direct contact.'),
(182, 'Pixilate', 'Normal-type moves become Fairy-type moves. The power of those moves is boosted a little.'),
(183, 'Gooey', 'Contact with the Pokémon lowers the attacker’s Speed stat.'),
(184, 'Aerilate', 'Normal-type moves become Flying-type moves. The power of those moves is boosted a little.'),
(185, 'Parental Bond', 'The parent and child attack one after the other.'),
(186, 'Dark Aura', 'Powers up the Dark-type moves of all Pokémon on the field.'),
(187, 'Fairy Aura', 'Powers up the Fairy-type moves of all Pokémon on the field.'),
(188, 'Aura Break', 'The effects of \"Aura\" Abilities are reversed to lower the power of affected moves.'),
(189, 'Primordial Sea', 'The Pokémon changes the weather to nullify Fire-type attacks.'),
(190, 'Desolate Land', 'The Pokémon changes the weather to nullify Water-type attacks.'),
(191, 'Delta Stream', 'The Pokémon changes the weather so that no moves are supereffective against the Flying type.'),
(192, 'Stamina', 'Boosts the Defense stat when the Pokémon is hit by an attack.'),
(193, 'Wimp Out', 'The Pokémon cowardly switches out when its HP drops to half or less.'),
(194, 'Emergency Exit', 'The Pokémon, sensing danger, switches out when its HP drops to half or less.'),
(195, 'Water Compaction', 'Boosts the Defense stat sharply when the Pokémon is hit by a Water-type move.'),
(196, 'Merciless', 'The Pokémon’s attacks become critical hits if the target is poisoned.'),
(197, 'Shields Down', 'When its HP drops to half or less, the Pokémon’s shell breaks and it becomes aggressive.'),
(198, 'Stakeout', 'Doubles the damage dealt to a target that has just switched into battle.'),
(199, 'Water Bubble', 'Lowers the power of Fire-type moves that hit the Pokémon and prevents it from being burned.'),
(200, 'Steelworker', 'Powers up Steel-type moves.'),
(201, 'Berserk', 'Boosts the Pokémon’s Sp. Atk stat when it takes a hit that causes its HP to drop to half or less.'),
(202, 'Slush Rush', 'Boosts the Pokémon’s Speed stat in snow.'),
(203, 'Long Reach', 'The Pokémon uses its moves without making contact with the target.'),
(204, 'Liquid Voice', 'Sound-based moves become Water-type moves.'),
(205, 'Triage', 'Gives priority to the Pokémon’s healing moves.'),
(206, 'Galvanize', 'Normal-type moves become Electric-type moves. The power of those moves is boosted a little.'),
(207, 'Surge Surfer', 'Doubles the Pokémon’s Speed stat on Electric Terrain.'),
(208, 'Schooling', 'When it has a lot of HP, the Pokémon forms a powerful school. It stops schooling when its HP is low.'),
(209, 'Disguise', 'Once per battle, the shroud that covers the Pokémon can protect it from an attack.'),
(210, 'Battle Bond', 'When the Pokémon knocks out a target, its bond with its Trainer is strengthened, and its Attack, Sp. Atk, and Speed stats are boosted.'),
(211, 'Power Construct', 'Cells gather to aid the Pokémon when its HP drops to half or less, causing it to change into its Complete Forme.'),
(212, 'Corrosion', 'The Pokémon can poison the target even if it’s a Steel or Poison type.'),
(213, 'Comatose', 'The Pokémon is always drowsing and will never wake up. It can attack while in its sleeping state.'),
(214, 'Queenly Majesty', 'The Pokémon’s majesty pressures opponents and makes them unable to use priority moves against the Pokémon or its allies.'),
(215, 'Innards Out', 'When the Pokémon is knocked out, it damages its attacker by the amount equal to the HP it had left before it was hit.'),
(216, 'Dancer', 'Whenever a dance move is used in battle, the Pokémon will copy the user to immediately perform that dance move itself.'),
(217, 'Battery', 'Powers up ally Pokémon’s special moves.'),
(218, 'Fluffy', 'Halves the damage taken from moves that make direct contact, but doubles that of Fire-type moves.'),
(219, 'Dazzling', 'The Pokémon dazzles its opponents, making them unable to use priority moves against the Pokémon or its allies.'),
(220, 'Soul-Heart', 'Boosts its Sp. Atk stat every time a Pokémon faints.'),
(221, 'Tangling Hair', 'Contact with the Pokémon lowers the attacker’s Speed stat.'),
(222, 'Receiver', 'The Pokémon copies the Ability of a defeated ally.'),
(223, 'Power of Alchemy', 'The Pokémon copies the Ability of a defeated ally.'),
(224, 'Beast Boost', 'Boosts the Pokémon’s most proficient stat every time it knocks out a target.'),
(225, 'RKS System', 'Changes the Pokémon’s type to match the memory disc it holds.'),
(226, 'Electric Surge', 'Turns the ground into Electric Terrain when the Pokémon enters a battle.'),
(227, 'Psychic Surge', 'Turns the ground into Psychic Terrain when the Pokémon enters a battle.'),
(228, 'Misty Surge', 'Turns the ground into Misty Terrain when the Pokémon enters a battle.'),
(229, 'Grassy Surge', 'Turns the ground into Grassy Terrain when the Pokémon enters a battle.'),
(230, 'Full Metal Body', 'Prevents other Pokémon’s moves or Abilities from lowering the Pokémon’s stats.'),
(231, 'Shadow Shield', 'Reduces the amount of damage the Pokémon takes while its HP is full.'),
(232, 'Prism Armor', 'Reduces the power of supereffective attacks that hit the Pokémon.'),
(233, 'Neuroforce', 'Powers up the Pokémon’s supereffective attacks even further.'),
(234, 'Intrepid Sword', 'Boosts the Pokémon’s Attack stat the first time the Pokémon enters a battle.'),
(235, 'Dauntless Shield', 'Boosts the Pokémon’s Defense stat the first time the Pokémon enters a battle.'),
(236, 'Libero', 'Changes the Pokémon’s type to the type of the move it’s about to use. This works only once each time the Pokémon enters battle.'),
(237, 'Ball Fetch', 'If the Pokémon is not holding an item, it will fetch the Poké Ball from the first failed throw of the battle.'),
(238, 'Cotton Down', 'When the Pokémon is hit by an attack, it scatters cotton fluff around and lowers the Speed stats of all Pokémon except itself.'),
(239, 'Propeller Tail', 'Ignores the effects of opposing Pokémon’s Abilities and moves that draw in moves.'),
(240, 'Mirror Armor', 'Bounces back only the stat-lowering effects that the Pokémon receives.'),
(241, 'Gulp Missile', 'When the Pokémon uses Surf or Dive, it will come back with prey. When it takes damage, it will spit out the prey to attack.'),
(242, 'Stalwart', 'Ignores the effects of opposing Pokémon’s Abilities and moves that draw in moves.'),
(243, 'Steam Engine', 'Boosts the Speed stat drastically when the Pokémon is hit by a Fire- or Water-type move.'),
(244, 'Punk Rock', 'Boosts the power of sound-based moves. The Pokémon also takes half the damage from these kinds of moves.'),
(245, 'Sand Spit', 'The Pokémon creates a sandstorm when it’s hit by an attack.'),
(246, 'Ice Scales', 'The Pokémon is protected by ice scales, which halve the damage taken from special moves.'),
(247, 'Ripen', 'Ripens Berries and doubles their effect.'),
(248, 'Ice Face', 'The Pokémon’s ice head can take a physical attack as a substitute, but the attack also changes the Pokémon’s appearance. The ice will be restored when it snows.'),
(249, 'Power Spot', 'Just being next to the Pokémon powers up moves.'),
(250, 'Mimicry', 'Changes the Pokémon’s type depending on the terrain.'),
(251, 'Screen Cleaner', 'When the Pokémon enters a battle, the effects of Light Screen, Reflect, and Aurora Veil are nullified for both opposing and ally Pokémon.'),
(252, 'Steely Spirit', 'Powers up the Steel-type moves of the Pokémon and its allies.'),
(253, 'Perish Body', 'When hit by a move that makes direct contact, the Pokémon and the attacker will faint after three turns unless they switch out of battle.'),
(254, 'Wandering Spirit', 'The Pokémon exchanges Abilities with a Pokémon that hits it with a move that makes direct contact.'),
(255, 'Gorilla Tactics', 'Boosts the Pokémon’s Attack stat but only allows the use of the first selected move.'),
(256, 'Neutralizing Gas', 'While the Pokémon is in the battle, the effects of all other Pokémon’s Abilities will be nullified or will not be triggered.'),
(257, 'Pastel Veil', 'Prevents the Pokémon and its allies from being poisoned.'),
(258, 'Hunger Switch', 'The Pokémon changes its form, alternating between its Full Belly Mode and Hangry Mode after the end of every turn.'),
(259, 'Quick Draw', 'Enables the Pokémon to move first occasionally.'),
(260, 'Unseen Fist', 'If the Pokémon uses moves that make direct contact, it can attack the target even if the target protects itself.'),
(261, 'Curious Medicine', 'When the Pokémon enters a battle, it scatters medicine from its shell, which removes all stat changes from allies.'),
(262, 'Transistor', 'Powers up Electric-type moves.'),
(263, 'Dragon\'s Maw', 'Powers up Dragon-type moves.'),
(264, 'Chilling Neigh', 'When the Pokémon knocks out a target, it utters a chilling neigh, which boosts its Attack stat.'),
(265, 'Grim Neigh', 'When the Pokémon knocks out a target, it utters a terrifying neigh, which boosts its Sp. Atk stat.'),
(266, 'As One', 'This Ability combines the effects of both Calyrex’s Unnerve Ability and Glastrier’s Chilling Neigh Ability.'),
(267, 'As One', 'This Ability combines the effects of both Calyrex’s Unnerve Ability and Spectrier’s Grim Neigh Ability.'),
(268, 'Lingering Aroma', 'Contact with the Pokémon changes the attacker’s Ability to Lingering Aroma.'),
(269, 'Seed Sower', 'Turns the ground into Grassy Terrain when the Pokémon is hit by an attack.'),
(270, 'Thermal Exchange', 'Boosts the Attack stat when the Pokémon is hit by a Fire-type move. The Pokémon also cannot be burned.'),
(271, 'Anger Shell', 'When an attack causes its HP to drop to half or less, the Pokémon gets angry. This lowers its Defense and Sp. Def stats but boosts its Attack, Sp. Atk, and Speed stats.'),
(272, 'Purifying Salt', 'The Pokémon’s pure salt protects it from status conditions and halves the damage taken from Ghost-type moves.'),
(273, 'Well-Baked Body', 'The Pokémon takes no damage when hit by Fire-type moves. Instead, its Defense stat is sharply boosted.'),
(274, 'Wind Rider', 'Boosts the Pokémon’s Attack stat if Tailwind takes effect or if the Pokémon is hit by a wind move. The Pokémon also takes no damage from wind moves.'),
(275, 'Guard Dog', 'Boosts the Pokémon’s Attack stat if intimidated. Moves and items that would force the Pokémon to switch out also fail to work.'),
(276, 'Rocky Payload', 'Powers up Rock-type moves.'),
(277, 'Wind Power', 'The Pokémon becomes charged when it is hit by a wind move, boosting the power of the next Electric-type move the Pokémon uses.'),
(278, 'Zero to Hero', 'The Pokémon transforms into its Hero Form when it switches out.'),
(279, 'Commander', 'When the Pokémon enters a battle, it goes inside the mouth of an ally Dondozo if one is on the field. The Pokémon then issues commands from there.'),
(280, 'Electromorphosis', 'The Pokémon becomes charged when it takes damage, boosting the power of the next Electric-type move the Pokémon uses.'),
(281, 'Protosynthesis', 'Boosts the Pokémon’s most proficient stat in harsh sunlight or if the Pokémon is holding Booster Energy.'),
(282, 'Quark Drive', 'Boosts the Pokémon’s most proficient stat on Electric Terrain or if the Pokémon is holding Booster Energy.'),
(283, 'Good as Gold', 'A body of pure, solid gold gives the Pokémon full immunity to other Pokémon’s status moves.'),
(284, 'Vessel of Ruin', 'The power of the Pokémon’s ruinous vessel lowers the Sp. Atk stats of all Pokémon except itself.'),
(285, 'Sword of Ruin', 'The power of the Pokémon’s ruinous sword lowers the Defense stats of all Pokémon except itself.'),
(286, 'Tablets of Ruin', 'The power of the Pokémon’s ruinous wooden tablets lowers the Attack stats of all Pokémon except itself.'),
(287, 'Beads of Ruin', 'The power of the Pokémon’s ruinous beads lowers the Sp. Def stats of all Pokémon except itself.'),
(288, 'Orichalcum Pulse', 'Turns the sunlight harsh when the Pokémon enters a battle. The ancient pulse thrumming through the Pokémon also boosts its Attack stat in harsh sunlight.'),
(289, 'Hadron Engine', 'Turns the ground into Electric Terrain when the Pokémon enters a battle. The futuristic engine within the Pokémon also boosts its Sp. Atk stat on Electric Terrain.'),
(290, 'Opportunist', 'If an opponent’s stat is boosted, the Pokémon seizes the opportunity to boost the same stat for itself.'),
(291, 'Cud Chew', 'When the Pokémon eats a Berry, it will regurgitate that Berry at the end of the next turn and eat it one more time.'),
(292, 'Sharpness', 'Powers up slicing moves.'),
(293, 'Supreme Overlord', 'When the Pokémon enters a battle, its Attack and Sp. Atk stats are slightly boosted for each of the allies in its party that have already been defeated.'),
(294, 'Costar', 'When the Pokémon enters a battle, it copies an ally’s stat changes.'),
(295, 'Toxic Debris', 'Scatters poison spikes at the feet of the opposing team when the Pokémon takes damage from physical moves.'),
(296, 'Armor Tail', 'The mysterious tail covering the Pokémon’s head makes opponents unable to use priority moves against the Pokémon or its allies.'),
(297, 'Earth Eater', 'If hit by a Ground-type move, the Pokémon has its HP restored instead of taking damage.'),
(298, 'Mycelium Might', 'The Pokémon will always act more slowly when using status moves, but these moves will be unimpeded by the Ability of the target.'),
(299, 'Hospitality', 'When the Pokémon enters a battle, it showers its ally with hospitality, restoring a small amount of the ally’s HP.'),
(300, 'Mind\'s Eye', 'The Pokémon ignores changes to opponents’ evasiveness, its accuracy can’t be lowered, and it can hit Ghost types with Normal- and Fighting-type moves.'),
(301, 'Embody Aspect', 'The Pokémon’s heart fills with memories, causing the Teal Mask to shine and the Pokémon’s Speed stat to be boosted.'),
(302, 'Embody Aspect', 'The Pokémon’s heart fills with memories, causing the Hearthflame Mask to shine and the Pokémon’s Attack stat to be boosted.'),
(303, 'Embody Aspect', 'The Pokémon’s heart fills with memories, causing the Wellspring Mask to shine and the Pokémon’s Sp. Def stat to be boosted.'),
(304, 'Embody Aspect', 'The Pokémon’s heart fills with memories, causing the Cornerstone Mask to shine and the Pokémon’s Defense stat to be boosted.'),
(305, 'Toxic Chain', 'The power of the Pokémon’s toxic chain may badly poison any target the Pokémon hits with a move.'),
(306, 'Supersweet Syrup', 'A sickly sweet scent spreads across the field the first time the Pokémon enters a battle, lowering the evasiveness of opposing Pokémon.'),
(307, 'Tera Shift', 'When the Pokémon enters a battle, it absorbs the energy around itself and transforms into its Terastal Form.'),
(308, 'Tera Shell', 'The Pokémon’s shell contains the powers of each type. All damage-dealing moves that hit the Pokémon when its HP is full will not be very effective.'),
(309, 'Teraform Zero', 'When Terapagos changes into its Stellar Form, it uses its hidden powers to eliminate all effects of weather and terrain, reducing them to zero.'),
(310, 'Poison Puppeteer', 'Pokémon poisoned by Pecharunt’s moves will also become confused.');

-- --------------------------------------------------------

--
-- Struttura della tabella `abilita_pokemon`
--

CREATE TABLE `abilita_pokemon` (
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL,
  `id_abilita` int(11) NOT NULL,
  `segreta` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `abilita_pokemon`
--

INSERT INTO `abilita_pokemon` (`cod`, `sec_form`, `id_abilita`, `segreta`) VALUES
(6, 'BASE', 1, 0),
(149, 'BASE', 2, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `battaglia`
--

CREATE TABLE `battaglia` (
  `id_battaglia` int(11) NOT NULL,
  `id_player1` int(11) NOT NULL,
  `id_player2` int(11) NOT NULL,
  `esito` tinyint(1) DEFAULT NULL,
  `pm` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `effetto_mossa`
--

CREATE TABLE `effetto_mossa` (
  `id_effetto` int(11) NOT NULL,
  `id_mossa` int(11) NOT NULL,
  `tipo_effetto` varchar(30) DEFAULT NULL,
  `valore_effetto` int(11) DEFAULT NULL,
  `bersaglio` varchar(30) DEFAULT NULL,
  `probabilita` int(11) DEFAULT NULL,
  `durata` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `mossa`
--

CREATE TABLE `mossa` (
  `id_mossa` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `descrizione` text DEFAULT NULL,
  `danno` int(11) DEFAULT NULL,
  `categoria` varchar(10) NOT NULL,
  `tipo` varchar(15) NOT NULL,
  `accuratezza` int(11) DEFAULT NULL,
  `priorita` int(11) DEFAULT 0,
  `pp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `mossa`
--

INSERT INTO `mossa` (`id_mossa`, `nome`, `descrizione`, `danno`, `categoria`, `tipo`, `accuratezza`, `priorita`, `pp`) VALUES
(53, 'Flamethrower', 'A powerful stream of fire. May burn the target.', 90, 'special', 'Fire', 100, 0, 15),
(200, 'Outrage', 'The user rampages and attacks for 2-3 turns. The user then becomes confused.', 120, 'physical', 'Dragon', 100, 0, 10);

-- --------------------------------------------------------

--
-- Struttura della tabella `mossa_x_pokemon`
--

CREATE TABLE `mossa_x_pokemon` (
  `id_mossa` int(11) NOT NULL,
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `mossa_x_pokemon`
--

INSERT INTO `mossa_x_pokemon` (`id_mossa`, `cod`, `sec_form`) VALUES
(53, 6, 'BASE'),
(200, 149, 'BASE');

-- --------------------------------------------------------

--
-- Struttura della tabella `pokemon`
--

CREATE TABLE `pokemon` (
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL DEFAULT 'BASE',
  `nome` varchar(15) NOT NULL,
  `tipo1` varchar(15) NOT NULL,
  `tipo2` varchar(15) DEFAULT NULL,
  `regione` int(11) NOT NULL,
  `uovo1` varchar(15) NOT NULL,
  `uovo2` varchar(15) DEFAULT NULL,
  `grado` varchar(20) NOT NULL,
  `originale` tinyint(1) NOT NULL,
  `HP` int(11) NOT NULL,
  `ATK` int(11) NOT NULL,
  `DEF` int(11) NOT NULL,
  `SP_ATK` int(11) NOT NULL,
  `SP_DEF` int(11) NOT NULL,
  `SPE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `pokemon`
--

INSERT INTO `pokemon` (`cod`, `sec_form`, `nome`, `tipo1`, `tipo2`, `regione`, `uovo1`, `uovo2`, `grado`, `originale`, `HP`, `ATK`, `DEF`, `SP_ATK`, `SP_DEF`, `SPE`) VALUES
(1, 'BASE', 'bulbasaur', 'grass', 'poison', 1, 'monster', 'grass', 'normale', 1, 45, 49, 49, 65, 65, 45),
(2, 'BASE', 'ivysaur', 'grass', 'poison', 1, 'monster', 'grass', 'normale', 1, 60, 62, 63, 80, 80, 60),
(3, 'BASE', 'venusaur', 'grass', 'poison', 1, 'monster', 'grass', 'normale', 1, 80, 82, 83, 100, 100, 80),
(4, 'BASE', 'charmander', 'fire', NULL, 1, 'monster', 'dragon', 'normale', 1, 39, 52, 43, 60, 50, 65),
(5, 'BASE', 'charmeleon', 'fire', NULL, 1, 'monster', 'dragon', 'normale', 1, 58, 64, 58, 80, 65, 80),
(6, 'BASE', 'charizard', 'fire', 'flying', 1, 'monster', 'dragon', 'normale', 1, 78, 84, 78, 109, 85, 100),
(7, 'BASE', 'squirtle', 'water', NULL, 1, 'monster', 'water1', 'normale', 1, 44, 48, 65, 50, 64, 43),
(8, 'BASE', 'wartortle', 'water', NULL, 1, 'monster', 'water1', 'normale', 1, 59, 63, 80, 65, 80, 58),
(9, 'BASE', 'blastoise', 'water', NULL, 1, 'monster', 'water1', 'normale', 1, 79, 83, 100, 85, 105, 78),
(10, 'BASE', 'caterpie', 'bug', NULL, 1, 'bug', NULL, 'normale', 1, 45, 30, 35, 20, 20, 45),
(11, 'BASE', 'metapod', 'bug', NULL, 1, 'bug', NULL, 'normale', 1, 50, 20, 55, 25, 25, 30),
(12, 'BASE', 'butterfree', 'bug', 'flying', 1, 'bug', NULL, 'normale', 1, 60, 45, 50, 90, 80, 70),
(13, 'BASE', 'weedle', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 40, 35, 30, 20, 20, 50),
(14, 'BASE', 'kakuna', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 45, 25, 50, 25, 25, 35),
(15, 'BASE', 'beedrill', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 65, 90, 40, 45, 80, 75),
(16, 'BASE', 'pidgey', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 40, 45, 40, 35, 35, 56),
(17, 'BASE', 'pidgeotto', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 63, 60, 55, 50, 50, 71),
(18, 'BASE', 'pidgeot', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 83, 80, 75, 70, 70, 101),
(19, 'BASE', 'rattata', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 30, 56, 35, 25, 35, 72),
(20, 'BASE', 'raticate', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 55, 81, 60, 50, 70, 97),
(21, 'BASE', 'spearow', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 40, 60, 30, 31, 31, 70),
(22, 'BASE', 'fearow', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 65, 90, 65, 61, 61, 100),
(23, 'BASE', 'ekans', 'poison', NULL, 1, 'field', 'dragon', 'normale', 1, 35, 60, 44, 40, 54, 55),
(24, 'BASE', 'arbok', 'poison', NULL, 1, 'field', 'dragon', 'normale', 1, 60, 95, 69, 65, 79, 80),
(25, 'BASE', 'pikachu', 'electric', NULL, 1, 'field', 'fairy', 'normale', 1, 35, 55, 40, 50, 50, 90),
(26, 'BASE', 'raichu', 'electric', NULL, 1, 'field', 'fairy', 'normale', 1, 60, 90, 55, 90, 80, 110),
(27, 'BASE', 'sandshrew', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 50, 75, 85, 20, 30, 40),
(28, 'BASE', 'sandslash', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 75, 100, 110, 45, 55, 65),
(29, 'BASE', 'nidoran_f', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 55, 47, 52, 40, 40, 41),
(30, 'BASE', 'nidorina', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 70, 62, 67, 55, 55, 56),
(31, 'BASE', 'nidoqueen', 'poison', 'ground', 1, 'monster', 'field', 'normale', 1, 90, 92, 87, 75, 85, 76),
(32, 'BASE', 'nidoran_m', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 46, 57, 40, 40, 40, 50),
(33, 'BASE', 'nidorino', 'poison', NULL, 1, 'monster', 'field', 'normale', 1, 61, 72, 57, 55, 55, 65),
(34, 'BASE', 'nidoking', 'poison', 'ground', 1, 'monster', 'field', 'normale', 1, 81, 102, 77, 85, 75, 85),
(35, 'BASE', 'clefairy', 'fairy', NULL, 1, 'fairy', NULL, 'normale', 1, 70, 45, 48, 60, 65, 35),
(36, 'BASE', 'clefable', 'fairy', NULL, 1, 'fairy', NULL, 'normale', 1, 95, 70, 73, 95, 90, 60),
(37, 'BASE', 'vulpix', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 38, 41, 40, 50, 65, 65),
(38, 'BASE', 'ninetales', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 73, 76, 75, 81, 100, 100),
(39, 'BASE', 'jigglypuff', 'normal', 'fairy', 1, 'fairy', NULL, 'normale', 1, 115, 45, 20, 45, 25, 20),
(40, 'BASE', 'wigglytuff', 'normal', 'fairy', 1, 'fairy', NULL, 'normale', 1, 140, 70, 45, 85, 50, 45),
(41, 'BASE', 'zubat', 'poison', 'flying', 1, 'flying', NULL, 'normale', 1, 40, 45, 35, 30, 40, 55),
(42, 'BASE', 'golbat', 'poison', 'flying', 1, 'flying', NULL, 'normale', 1, 75, 80, 70, 65, 75, 90),
(43, 'BASE', 'oddish', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 45, 50, 55, 75, 65, 30),
(44, 'BASE', 'gloom', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 60, 65, 70, 85, 75, 40),
(45, 'BASE', 'vileplume', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 75, 80, 85, 110, 90, 50),
(46, 'BASE', 'paras', 'bug', 'grass', 1, 'bug', 'grass', 'normale', 1, 35, 70, 55, 45, 55, 25),
(47, 'BASE', 'parasect', 'bug', 'grass', 1, 'bug', 'grass', 'normale', 1, 60, 95, 80, 60, 80, 30),
(48, 'BASE', 'venonat', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 60, 55, 50, 40, 55, 45),
(49, 'BASE', 'venomoth', 'bug', 'poison', 1, 'bug', NULL, 'normale', 1, 70, 65, 60, 90, 75, 90),
(50, 'BASE', 'diglett', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 10, 55, 25, 35, 45, 95),
(51, 'BASE', 'dugtrio', 'ground', NULL, 1, 'field', NULL, 'normale', 1, 35, 100, 50, 50, 70, 120),
(52, 'BASE', 'meowth', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 40, 45, 35, 40, 40, 90),
(53, 'BASE', 'persian', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 65, 70, 60, 65, 65, 115),
(54, 'BASE', 'psyduck', 'water', NULL, 1, 'water1', 'field', 'normale', 1, 50, 52, 48, 65, 50, 55),
(55, 'BASE', 'golduck', 'water', NULL, 1, 'water1', 'field', 'normale', 1, 80, 82, 78, 95, 80, 85),
(56, 'BASE', 'mankey', 'fighting', NULL, 1, 'field', NULL, 'normale', 1, 40, 80, 35, 35, 45, 70),
(57, 'BASE', 'primeape', 'fighting', NULL, 1, 'field', NULL, 'normale', 1, 65, 105, 60, 60, 70, 95),
(58, 'BASE', 'growlithe', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 55, 70, 45, 70, 50, 60),
(59, 'BASE', 'arcanine', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 90, 110, 80, 100, 80, 95),
(60, 'BASE', 'poliwag', 'water', NULL, 1, 'water1', NULL, 'normale', 1, 40, 50, 40, 40, 40, 90),
(61, 'BASE', 'poliwhirl', 'water', NULL, 1, 'water1', NULL, 'normale', 1, 65, 65, 65, 50, 50, 90),
(62, 'BASE', 'poliwrath', 'water', 'fighting', 1, 'water1', NULL, 'normale', 1, 90, 95, 95, 70, 90, 70),
(63, 'BASE', 'abra', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 25, 20, 15, 105, 55, 90),
(64, 'BASE', 'kadabra', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 40, 35, 30, 120, 70, 105),
(65, 'BASE', 'alakazam', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 55, 50, 45, 135, 95, 120),
(66, 'BASE', 'machop', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 70, 80, 50, 35, 35, 35),
(67, 'BASE', 'machoke', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 80, 100, 70, 50, 60, 45),
(68, 'BASE', 'machamp', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 90, 130, 80, 65, 85, 55),
(69, 'BASE', 'bellsprout', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 50, 75, 35, 70, 30, 40),
(70, 'BASE', 'weepinbell', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 65, 90, 50, 85, 45, 55),
(71, 'BASE', 'victreebel', 'grass', 'poison', 1, 'grass', NULL, 'normale', 1, 80, 105, 65, 100, 70, 70),
(72, 'BASE', 'tentacool', 'water', 'poison', 1, 'water3', NULL, 'normale', 1, 40, 40, 35, 50, 100, 70),
(73, 'BASE', 'tentacruel', 'water', 'poison', 1, 'water3', NULL, 'normale', 1, 80, 70, 65, 80, 120, 100),
(74, 'BASE', 'geodude', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 40, 80, 100, 30, 30, 20),
(75, 'BASE', 'graveler', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 55, 95, 115, 45, 45, 35),
(76, 'BASE', 'golem', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 80, 120, 130, 55, 65, 45),
(77, 'BASE', 'ponyta', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 50, 85, 55, 65, 65, 90),
(78, 'BASE', 'rapidash', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 65, 100, 70, 80, 80, 105),
(79, 'BASE', 'slowpoke', 'water', 'psychic', 1, 'monster', 'water1', 'normale', 1, 90, 65, 65, 40, 40, 15),
(80, 'BASE', 'slowbro', 'water', 'psychic', 1, 'monster', 'water1', 'normale', 1, 95, 75, 110, 100, 80, 30),
(81, 'BASE', 'magnemite', 'electric', 'steel', 1, 'mineral', NULL, 'normale', 1, 25, 35, 70, 95, 55, 45),
(82, 'BASE', 'magneton', 'electric', 'steel', 1, 'mineral', NULL, 'normale', 1, 50, 60, 95, 120, 70, 70),
(83, 'BASE', 'farfetchd', 'normal', 'flying', 1, 'flying', 'field', 'normale', 1, 52, 90, 55, 58, 62, 60),
(84, 'BASE', 'doduo', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 35, 85, 45, 35, 35, 75),
(85, 'BASE', 'dodrio', 'normal', 'flying', 1, 'flying', NULL, 'normale', 1, 60, 110, 70, 60, 60, 100),
(86, 'BASE', 'seel', 'water', NULL, 1, 'water1', 'field', 'normale', 1, 65, 45, 55, 45, 70, 45),
(87, 'BASE', 'dewgong', 'water', 'ice', 1, 'water1', 'field', 'normale', 1, 90, 70, 80, 70, 95, 70),
(88, 'BASE', 'grimer', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 80, 80, 50, 40, 50, 25),
(89, 'BASE', 'muk', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 105, 105, 75, 65, 100, 50),
(90, 'BASE', 'shellder', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 30, 65, 100, 45, 25, 40),
(91, 'BASE', 'cloyster', 'water', 'ice', 1, 'water3', NULL, 'normale', 1, 50, 95, 180, 85, 45, 70),
(92, 'BASE', 'gastly', 'ghost', 'poison', 1, 'amorphous', NULL, 'normale', 1, 30, 35, 30, 100, 35, 80),
(93, 'BASE', 'haunter', 'ghost', 'poison', 1, 'amorphous', NULL, 'normale', 1, 45, 50, 45, 115, 55, 95),
(94, 'BASE', 'gengar', 'ghost', 'poison', 1, 'amorphous', NULL, 'normale', 1, 60, 65, 60, 130, 75, 110),
(95, 'BASE', 'onix', 'rock', 'ground', 1, 'mineral', NULL, 'normale', 1, 35, 45, 160, 30, 45, 70),
(96, 'BASE', 'drowzee', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 60, 48, 45, 43, 90, 42),
(97, 'BASE', 'hypno', 'psychic', NULL, 1, 'humanlike', NULL, 'normale', 1, 85, 73, 70, 73, 115, 67),
(98, 'BASE', 'krabby', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 30, 105, 90, 25, 25, 50),
(99, 'BASE', 'kingler', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 55, 130, 115, 50, 50, 75),
(100, 'BASE', 'voltorb', 'electric', NULL, 1, 'mineral', NULL, 'normale', 1, 40, 30, 50, 55, 55, 100),
(101, 'BASE', 'electrode', 'electric', NULL, 1, 'mineral', NULL, 'normale', 1, 60, 50, 70, 80, 80, 150),
(102, 'BASE', 'exeggcute', 'grass', 'psychic', 1, 'grass', NULL, 'normale', 1, 60, 40, 80, 60, 45, 40),
(103, 'BASE', 'exeggutor', 'grass', 'psychic', 1, 'grass', NULL, 'normale', 1, 95, 95, 85, 125, 75, 55),
(104, 'BASE', 'cubone', 'ground', NULL, 1, 'monster', NULL, 'normale', 1, 50, 50, 95, 40, 50, 35),
(105, 'BASE', 'marowak', 'ground', NULL, 1, 'monster', NULL, 'normale', 1, 60, 80, 110, 50, 80, 45),
(106, 'BASE', 'hitmonlee', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 50, 120, 53, 35, 110, 87),
(107, 'BASE', 'hitmonchan', 'fighting', NULL, 1, 'humanlike', NULL, 'normale', 1, 50, 105, 79, 35, 110, 76),
(108, 'BASE', 'lickitung', 'normal', NULL, 1, 'monster', NULL, 'normale', 1, 90, 55, 75, 60, 75, 30),
(109, 'BASE', 'koffing', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 40, 65, 95, 60, 45, 35),
(110, 'BASE', 'weezing', 'poison', NULL, 1, 'amorphous', NULL, 'normale', 1, 65, 90, 120, 85, 70, 60),
(111, 'BASE', 'rhyhorn', 'ground', 'rock', 1, 'monster', 'field', 'normale', 1, 80, 85, 95, 30, 30, 25),
(112, 'BASE', 'rhydon', 'ground', 'rock', 1, 'monster', 'field', 'normale', 1, 105, 130, 120, 45, 45, 40),
(113, 'BASE', 'chansey', 'normal', NULL, 1, 'fairy', NULL, 'normale', 1, 250, 5, 5, 35, 105, 50),
(114, 'BASE', 'tangela', 'grass', NULL, 1, 'grass', NULL, 'normale', 1, 65, 55, 115, 100, 40, 60),
(115, 'BASE', 'kangaskhan', 'normal', NULL, 1, 'monster', NULL, 'normale', 1, 105, 95, 80, 40, 80, 90),
(116, 'BASE', 'horsea', 'water', NULL, 1, 'water1', 'dragon', 'normale', 1, 30, 40, 70, 70, 25, 60),
(117, 'BASE', 'seadra', 'water', NULL, 1, 'water1', 'dragon', 'normale', 1, 55, 65, 95, 95, 45, 85),
(118, 'BASE', 'goldeen', 'water', NULL, 1, 'water2', NULL, 'normale', 1, 45, 67, 60, 35, 50, 63),
(119, 'BASE', 'seaking', 'water', NULL, 1, 'water2', NULL, 'normale', 1, 80, 92, 65, 65, 80, 68),
(120, 'BASE', 'staryu', 'water', NULL, 1, 'water3', NULL, 'normale', 1, 30, 45, 55, 70, 55, 85),
(121, 'BASE', 'starmie', 'water', 'psychic', 1, 'water3', NULL, 'normale', 1, 60, 75, 85, 100, 85, 115),
(122, 'BASE', 'mr_mime', 'psychic', 'fairy', 1, 'humanlike', NULL, 'normale', 1, 40, 45, 65, 100, 120, 90),
(123, 'BASE', 'scyther', 'bug', 'flying', 1, 'bug', NULL, 'normale', 1, 70, 110, 80, 55, 80, 105),
(124, 'BASE', 'jynx', 'ice', 'psychic', 1, 'humanlike', NULL, 'normale', 1, 65, 50, 35, 115, 95, 95),
(125, 'BASE', 'electabuzz', 'electric', NULL, 1, 'humanlike', NULL, 'normale', 1, 65, 83, 57, 95, 85, 105),
(126, 'BASE', 'magmar', 'fire', NULL, 1, 'humanlike', NULL, 'normale', 1, 65, 95, 57, 100, 85, 93),
(127, 'BASE', 'pinsir', 'bug', NULL, 1, 'bug', NULL, 'normale', 1, 65, 125, 100, 55, 70, 85),
(128, 'BASE', 'tauros', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 75, 100, 95, 40, 70, 110),
(129, 'BASE', 'magikarp', 'water', NULL, 1, 'water2', 'dragon', 'normale', 1, 20, 10, 55, 15, 20, 80),
(130, 'BASE', 'gyarados', 'water', 'flying', 1, 'water2', 'dragon', 'normale', 1, 95, 125, 79, 60, 100, 81),
(131, 'BASE', 'lapras', 'water', 'ice', 1, 'monster', 'water1', 'normale', 1, 130, 85, 80, 85, 95, 60),
(132, 'BASE', 'ditto', 'normal', NULL, 1, 'ditto', NULL, 'normale', 1, 48, 48, 48, 48, 48, 48),
(133, 'BASE', 'eevee', 'normal', NULL, 1, 'field', NULL, 'normale', 1, 55, 55, 50, 45, 65, 55),
(134, 'BASE', 'vaporeon', 'water', NULL, 1, 'field', NULL, 'normale', 1, 130, 65, 60, 110, 95, 65),
(135, 'BASE', 'jolteon', 'electric', NULL, 1, 'field', NULL, 'normale', 1, 65, 65, 60, 110, 95, 130),
(136, 'BASE', 'flareon', 'fire', NULL, 1, 'field', NULL, 'normale', 1, 65, 130, 60, 95, 110, 65),
(137, 'BASE', 'porygon', 'normal', NULL, 1, 'mineral', NULL, 'normale', 1, 65, 60, 70, 85, 75, 40),
(138, 'BASE', 'omanyte', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 35, 40, 100, 90, 55, 35),
(139, 'BASE', 'omastar', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 70, 60, 125, 115, 70, 55),
(140, 'BASE', 'kabuto', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 30, 80, 90, 55, 45, 55),
(141, 'BASE', 'kabutops', 'rock', 'water', 1, 'water1', 'water3', 'normale', 1, 60, 115, 105, 65, 70, 80),
(142, 'BASE', 'aerodactyl', 'rock', 'flying', 1, 'flying', NULL, 'normale', 1, 80, 105, 65, 60, 75, 130),
(143, 'BASE', 'snorlax', 'normal', NULL, 1, 'monster', NULL, 'normale', 1, 160, 110, 65, 65, 110, 30),
(144, 'BASE', 'articuno', 'ice', 'flying', 1, 'undiscovered', NULL, 'leggendario', 1, 90, 85, 100, 95, 125, 85),
(145, 'BASE', 'zapdos', 'electric', 'flying', 1, 'undiscovered', NULL, 'leggendario', 1, 90, 90, 85, 125, 90, 100),
(146, 'BASE', 'moltres', 'fire', 'flying', 1, 'undiscovered', NULL, 'leggendario', 1, 90, 100, 90, 125, 85, 90),
(147, 'BASE', 'dratini', 'dragon', NULL, 1, 'water1', 'dragon', 'normale', 1, 41, 64, 45, 50, 50, 50),
(148, 'BASE', 'dragonair', 'dragon', NULL, 1, 'water1', 'dragon', 'normale', 1, 61, 84, 65, 70, 70, 70),
(149, 'BASE', 'dragonite', 'dragon', 'flying', 1, 'water1', 'dragon', 'normale', 1, 91, 134, 95, 100, 100, 80),
(150, 'BASE', 'mewtwo', 'psychic', NULL, 1, 'undiscovered', NULL, 'leggendario', 1, 106, 110, 90, 154, 90, 130),
(151, 'BASE', 'mew', 'psychic', NULL, 1, 'undiscovered', NULL, 'mitico', 1, 100, 100, 100, 100, 100, 100);

-- --------------------------------------------------------

--
-- Struttura della tabella `pokemon_utente`
--

CREATE TABLE `pokemon_utente` (
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL,
  `id_utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `preferiti`
--

CREATE TABLE `preferiti` (
  `id_utente` int(11) NOT NULL,
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `regione`
--

CREATE TABLE `regione` (
  `generazione` int(11) NOT NULL,
  `nome` varchar(30) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `regione`
--

INSERT INTO `regione` (`generazione`, `nome`, `descrizione`) VALUES
(1, 'kanto', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra`
--

CREATE TABLE `squadra` (
  `id_squadra` int(11) NOT NULL,
  `codice_utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `squadra`
--

INSERT INTO `squadra` (`id_squadra`, `codice_utente`) VALUES
(7, 7),
(8, 8);

-- --------------------------------------------------------

--
-- Struttura della tabella `squadra_pokemon`
--

CREATE TABLE `squadra_pokemon` (
  `id_squadra` int(11) NOT NULL,
  `slot` int(11) NOT NULL CHECK (`slot` between 1 and 6),
  `cod` int(11) NOT NULL,
  `sec_form` varchar(20) NOT NULL,
  `mossa1` int(11) NOT NULL,
  `mossa2` int(11) DEFAULT NULL,
  `mossa3` int(11) DEFAULT NULL,
  `mossa4` int(11) DEFAULT NULL,
  `abilita_scelta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `squadra_pokemon`
--

INSERT INTO `squadra_pokemon` (`id_squadra`, `slot`, `cod`, `sec_form`, `mossa1`, `mossa2`, `mossa3`, `mossa4`, `abilita_scelta`) VALUES
(7, 1, 6, 'BASE', 53, NULL, NULL, NULL, 1),
(7, 2, 149, 'BASE', 200, NULL, NULL, NULL, 2),
(8, 1, 6, 'BASE', 53, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `tipo`
--

CREATE TABLE `tipo` (
  `id_t` int(11) NOT NULL,
  `nome` varchar(15) NOT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `tipo`
--

INSERT INTO `tipo` (`id_t`, `nome`, `descrizione`) VALUES
(1, 'normal', ''),
(2, 'fire', ''),
(3, 'water', ''),
(4, 'electric', ''),
(5, 'grass', ''),
(6, 'ice', ''),
(7, 'fighting', ''),
(8, 'poison', ''),
(9, 'ground', ''),
(10, 'flying', ''),
(11, 'psychic', ''),
(12, 'bug', ''),
(13, 'rock', ''),
(14, 'ghost', ''),
(15, 'dragon', ''),
(16, 'dark', ''),
(17, 'steel', ''),
(18, 'fairy', '');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `codice` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`codice`, `password`, `nome`) VALUES
(7, '$2y$10$lbUA0PeQGW/4swnZG3rqH.ZR10u08q37sCsOqEHUP8JBxw7MCdbQi', 'prova'),
(8, '$2y$10$uo7NJWyVI03vX53KrgS1aulXFeHzJrom9FlE5.NBoLBvxOepixzuG', 'madreperla');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `abilita`
--
ALTER TABLE `abilita`
  ADD PRIMARY KEY (`id_abilita`);

--
-- Indici per le tabelle `abilita_pokemon`
--
ALTER TABLE `abilita_pokemon`
  ADD PRIMARY KEY (`cod`,`sec_form`,`id_abilita`),
  ADD KEY `id_abilita` (`id_abilita`);

--
-- Indici per le tabelle `battaglia`
--
ALTER TABLE `battaglia`
  ADD PRIMARY KEY (`id_battaglia`),
  ADD KEY `id_player1` (`id_player1`),
  ADD KEY `id_player2` (`id_player2`);

--
-- Indici per le tabelle `effetto_mossa`
--
ALTER TABLE `effetto_mossa`
  ADD PRIMARY KEY (`id_effetto`),
  ADD KEY `id_mossa` (`id_mossa`);

--
-- Indici per le tabelle `mossa`
--
ALTER TABLE `mossa`
  ADD PRIMARY KEY (`id_mossa`),
  ADD KEY `tipo` (`tipo`);

--
-- Indici per le tabelle `mossa_x_pokemon`
--
ALTER TABLE `mossa_x_pokemon`
  ADD PRIMARY KEY (`id_mossa`,`cod`,`sec_form`),
  ADD KEY `cod` (`cod`,`sec_form`);

--
-- Indici per le tabelle `pokemon`
--
ALTER TABLE `pokemon`
  ADD PRIMARY KEY (`cod`,`sec_form`),
  ADD KEY `tipo1` (`tipo1`),
  ADD KEY `tipo2` (`tipo2`),
  ADD KEY `regione` (`regione`);

--
-- Indici per le tabelle `pokemon_utente`
--
ALTER TABLE `pokemon_utente`
  ADD PRIMARY KEY (`cod`,`sec_form`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `preferiti`
--
ALTER TABLE `preferiti`
  ADD PRIMARY KEY (`id_utente`,`cod`,`sec_form`),
  ADD KEY `cod` (`cod`,`sec_form`);

--
-- Indici per le tabelle `regione`
--
ALTER TABLE `regione`
  ADD PRIMARY KEY (`generazione`);

--
-- Indici per le tabelle `squadra`
--
ALTER TABLE `squadra`
  ADD PRIMARY KEY (`id_squadra`),
  ADD KEY `codice_utente` (`codice_utente`);

--
-- Indici per le tabelle `squadra_pokemon`
--
ALTER TABLE `squadra_pokemon`
  ADD PRIMARY KEY (`id_squadra`,`slot`),
  ADD KEY `cod` (`cod`,`sec_form`),
  ADD KEY `mossa1` (`mossa1`),
  ADD KEY `mossa2` (`mossa2`),
  ADD KEY `mossa3` (`mossa3`),
  ADD KEY `mossa4` (`mossa4`),
  ADD KEY `abilita_scelta` (`abilita_scelta`);

--
-- Indici per le tabelle `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`id_t`),
  ADD UNIQUE KEY `nome` (`nome`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`codice`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `abilita`
--
ALTER TABLE `abilita`
  MODIFY `id_abilita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `codice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `abilita_pokemon`
--
ALTER TABLE `abilita_pokemon`
  ADD CONSTRAINT `abilita_pokemon_ibfk_1` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`),
  ADD CONSTRAINT `abilita_pokemon_ibfk_2` FOREIGN KEY (`id_abilita`) REFERENCES `abilita` (`id_abilita`);

--
-- Limiti per la tabella `battaglia`
--
ALTER TABLE `battaglia`
  ADD CONSTRAINT `battaglia_ibfk_1` FOREIGN KEY (`id_player1`) REFERENCES `utente` (`codice`),
  ADD CONSTRAINT `battaglia_ibfk_2` FOREIGN KEY (`id_player2`) REFERENCES `utente` (`codice`);

--
-- Limiti per la tabella `effetto_mossa`
--
ALTER TABLE `effetto_mossa`
  ADD CONSTRAINT `effetto_mossa_ibfk_1` FOREIGN KEY (`id_mossa`) REFERENCES `mossa` (`id_mossa`);

--
-- Limiti per la tabella `mossa`
--
ALTER TABLE `mossa`
  ADD CONSTRAINT `mossa_ibfk_1` FOREIGN KEY (`tipo`) REFERENCES `tipo` (`nome`);

--
-- Limiti per la tabella `mossa_x_pokemon`
--
ALTER TABLE `mossa_x_pokemon`
  ADD CONSTRAINT `mossa_x_pokemon_ibfk_1` FOREIGN KEY (`id_mossa`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `mossa_x_pokemon_ibfk_2` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`);

--
-- Limiti per la tabella `pokemon`
--
ALTER TABLE `pokemon`
  ADD CONSTRAINT `pokemon_ibfk_1` FOREIGN KEY (`tipo1`) REFERENCES `tipo` (`nome`),
  ADD CONSTRAINT `pokemon_ibfk_2` FOREIGN KEY (`tipo2`) REFERENCES `tipo` (`nome`),
  ADD CONSTRAINT `pokemon_ibfk_3` FOREIGN KEY (`regione`) REFERENCES `regione` (`generazione`);

--
-- Limiti per la tabella `pokemon_utente`
--
ALTER TABLE `pokemon_utente`
  ADD CONSTRAINT `pokemon_utente_ibfk_1` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`),
  ADD CONSTRAINT `pokemon_utente_ibfk_2` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`codice`);

--
-- Limiti per la tabella `preferiti`
--
ALTER TABLE `preferiti`
  ADD CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`codice`),
  ADD CONSTRAINT `preferiti_ibfk_2` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`);

--
-- Limiti per la tabella `squadra`
--
ALTER TABLE `squadra`
  ADD CONSTRAINT `squadra_ibfk_1` FOREIGN KEY (`codice_utente`) REFERENCES `utente` (`codice`);

--
-- Limiti per la tabella `squadra_pokemon`
--
ALTER TABLE `squadra_pokemon`
  ADD CONSTRAINT `squadra_pokemon_ibfk_1` FOREIGN KEY (`id_squadra`) REFERENCES `squadra` (`id_squadra`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_2` FOREIGN KEY (`cod`,`sec_form`) REFERENCES `pokemon` (`cod`, `sec_form`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_3` FOREIGN KEY (`mossa1`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_4` FOREIGN KEY (`mossa2`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_5` FOREIGN KEY (`mossa3`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_6` FOREIGN KEY (`mossa4`) REFERENCES `mossa` (`id_mossa`),
  ADD CONSTRAINT `squadra_pokemon_ibfk_7` FOREIGN KEY (`abilita_scelta`) REFERENCES `abilita` (`id_abilita`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
