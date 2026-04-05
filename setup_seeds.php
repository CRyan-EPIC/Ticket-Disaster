<?php
/**
 * Seed data functions for all themes.
 * Extracted from setup.php so they can be required without HTML side effects.
 */

function getSeedData($themeKey) {
    switch ($themeKey) {
        case 'sports': return getSportsSeeds();
        case 'games':  return getGamesSeeds();
        case 'cars':   return getCarsSeeds();
        default:       return getMusicSeeds();
    }
}

function getMusicSeeds() {
    return [
        ['Tame Impala', 'Red Rocks Amphitheatre', 'Morrison', '2026-06-15', 95.00, 4500, 'Psychedelic Rock', '', 'Kevin Parker brings his immersive psychedelic experience to the stunning Red Rocks.', 'https://open.spotify.com/search/Tame%20Impala'],
        ['Khruangbin', 'Red Rocks Amphitheatre', 'Morrison', '2026-07-04', 85.00, 5000, 'Psychedelic Soul', '', 'The Houston trio delivers their hypnotic grooves under the stars at Red Rocks.', 'https://open.spotify.com/search/Khruangbin'],
        ['Goose', 'Red Rocks Amphitheatre', 'Morrison', '2026-07-18', 75.00, 6000, 'Jam Band', '', 'Two nights of improvisational rock at the most iconic amphitheatre in the world.', 'https://open.spotify.com/search/Goose'],
        ['Kendrick Lamar', 'Ball Arena', 'Denver', '2026-08-10', 150.00, 8000, 'Hip Hop', '', 'The Pulitzer Prize-winning rapper brings his arena tour to Denver.', 'https://open.spotify.com/search/Kendrick%20Lamar'],
        ['Billie Eilish', 'Ball Arena', 'Denver', '2026-09-20', 130.00, 7500, 'Pop/Alternative', '', 'Billie Eilish performs her hauntingly beautiful catalog at Ball Arena.', 'https://open.spotify.com/search/Billie%20Eilish'],
        ['The War on Drugs', 'Mission Ballroom', 'Denver', '2026-06-22', 55.00, 3500, 'Indie Rock', '', 'Adam Granduciel and the band bring their expansive rock sound to Mission Ballroom.', 'https://open.spotify.com/search/The%20War%20on%20Drugs'],
        ['Thundercat', 'Ogden Theatre', 'Denver', '2026-07-08', 45.00, 1200, 'Funk/Jazz', '', 'Bass virtuoso Thundercat brings his genre-defying live show to the Ogden.', 'https://open.spotify.com/search/Thundercat'],
        ['Japanese Breakfast', 'Gothic Theatre', 'Englewood', '2026-06-28', 40.00, 800, 'Indie Pop', '', 'Michelle Zauner performs songs from Jubilee and beyond at the Gothic.', 'https://open.spotify.com/search/Japanese%20Breakfast'],
        ['Tyler, The Creator', "Fiddler's Green Amphitheatre", 'Greenwood Village', '2026-08-15', 95.00, 5000, 'Hip Hop', '', "Tyler brings his creative vision to Fiddler's Green.", 'https://open.spotify.com/search/Tyler%20The%20Creator'],
        ['King Gizzard & The Lizard Wizard', 'Red Rocks Amphitheatre', 'Morrison', '2026-08-22', 70.00, 6000, 'Psychedelic Rock', '', 'The Aussie psych-rock legends return to Red Rocks for a marathon set.', 'https://open.spotify.com/search/King%20Gizzard'],
        ['Turnstile', 'Mission Ballroom', 'Denver', '2026-07-12', 50.00, 3000, 'Hardcore Punk', '', 'The Baltimore hardcore band brings non-stop energy to Mission Ballroom.', 'https://open.spotify.com/search/Turnstile'],
        ['Mac DeMarco', 'Ogden Theatre', 'Denver', '2026-09-05', 45.00, 1200, 'Indie Rock', '', 'Laid-back vibes and jangly guitar at the Ogden Theatre.', 'https://open.spotify.com/search/Mac%20DeMarco'],
        ['STS9', 'Red Rocks Amphitheatre', 'Morrison', '2026-09-12', 65.00, 7000, 'Electronic/Jam', '', 'Sound Tribe returns to their spiritual home at Red Rocks.', 'https://open.spotify.com/search/STS9'],
        ['Big Wild', 'Mission Ballroom', 'Denver', '2026-06-18', 40.00, 3500, 'Electronic', '', 'Jackson Stell brings his feel-good electronic-live hybrid to Denver.', 'https://open.spotify.com/search/Big%20Wild'],
        ['Nathaniel Rateliff', 'Red Rocks Amphitheatre', 'Morrison', '2026-07-25', 80.00, 8000, 'Folk Rock', '', "Denver's own Nathaniel Rateliff headlines Red Rocks.", 'https://open.spotify.com/search/Nathaniel%20Rateliff'],
        ['Russ', 'Fillmore Auditorium', 'Denver', '2026-08-02', 55.00, 2500, 'Hip Hop', '', 'Russ brings his independent hip hop movement to the Fillmore.', 'https://open.spotify.com/search/Russ'],
        ['Lettuce', 'Boulder Theater', 'Boulder', '2026-06-20', 35.00, 700, 'Funk/Jazz', '', 'The funk masters take over Boulder Theater for two nights.', 'https://open.spotify.com/search/Lettuce'],
        ['Vulfpeck', 'Boulder Theater', 'Boulder', '2026-07-10', 50.00, 700, 'Funk', '', 'The internet-born funk sensation brings their minimalist grooves to Boulder.', 'https://open.spotify.com/search/Vulfpeck'],
        ['Billy Strings', 'Red Rocks Amphitheatre', 'Morrison', '2026-08-30', 85.00, 8000, 'Bluegrass', '', 'The bluegrass prodigy shreds at Red Rocks for three nights.', 'https://open.spotify.com/search/Billy%20Strings'],
        ['Pinegrove', 'Fox Theatre', 'Boulder', '2026-06-25', 30.00, 500, 'Indie Rock', '', 'Heartfelt indie rock at the intimate Fox Theatre in Boulder.', 'https://open.spotify.com/search/Pinegrove'],
        ["Umphrey's McGee", 'Red Rocks Amphitheatre', 'Morrison', '2026-07-30', 65.00, 6000, 'Jam Rock', '', "Precision prog-jam at the world's best venue.", 'https://open.spotify.com/search/Umphreys%20McGee'],
        ['RUFUS DU SOL', 'Red Rocks Amphitheatre', 'Morrison', '2026-08-08', 90.00, 7000, 'Electronic', '', 'The Australian electronic trio returns to Red Rocks.', 'https://open.spotify.com/search/RUFUS%20DU%20SOL'],
        ['The Avett Brothers', 'Red Rocks Amphitheatre', 'Morrison', '2026-09-18', 75.00, 7500, 'Folk Rock', '', 'Americana classics and new material under the Colorado sky.', 'https://open.spotify.com/search/The%20Avett%20Brothers'],
        ['Yung Gravy', 'Aggie Theatre', 'Fort Collins', '2026-07-05', 40.00, 900, 'Hip Hop/Comedy', '', 'The viral sensation brings smooth flows to Aggie Theatre.', 'https://open.spotify.com/search/Yung%20Gravy'],
        ['Trampled by Turtles', "Washington's", 'Fort Collins', '2026-08-12', 35.00, 500, 'Bluegrass', '', "High-energy bluegrass at Washington's in Fort Collins.", 'https://open.spotify.com/search/Trampled%20by%20Turtles'],
        ['Railroad Earth', 'The Mishawaka', 'Bellvue', '2026-07-20', 40.00, 600, 'Jam Bluegrass', '', 'Jamgrass legends at the riverside Mishawaka amphitheatre.', 'https://open.spotify.com/search/Railroad%20Earth'],
        ['Glass Animals', "Fiddler's Green Amphitheatre", 'Greenwood Village', '2026-08-18', 70.00, 5000, 'Indie Pop', '', 'Heat Waves and more under the Colorado stars.', 'https://open.spotify.com/search/Glass%20Animals'],
        ['Mt. Joy', 'Mission Ballroom', 'Denver', '2026-06-30', 45.00, 3000, 'Indie Rock', '', 'Philly indie rockers bring the vibes to Mission Ballroom.', 'https://open.spotify.com/search/Mt.%20Joy'],
        ['Rezz', 'Mission Ballroom', 'Denver', '2026-09-08', 50.00, 3500, 'Electronic', '', 'Space Mom brings her hypnotic bass music to Denver.', 'https://open.spotify.com/search/Rezz'],
        ['Fleet Foxes', 'Red Rocks Amphitheatre', 'Morrison', '2026-09-25', 80.00, 6000, 'Indie Folk', '', "Robin Pecknold's harmonic folk masterpieces echo through Red Rocks.", 'https://open.spotify.com/search/Fleet%20Foxes'],
    ];
}

function getSportsSeeds() {
    return [
        ['Nuggets vs Lakers', 'Ball Arena', 'Denver', '2026-10-22', 125.00, 19520, 'Basketball (NBA)', '', 'Season opener! The defending champs host LeBron and the Lakers.', ''],
        ['Nuggets vs Celtics', 'Ball Arena', 'Denver', '2026-11-15', 150.00, 19520, 'Basketball (NBA)', '', 'NBA Finals rematch at Ball Arena.', ''],
        ['Nuggets vs Warriors', 'Ball Arena', 'Denver', '2026-12-05', 140.00, 19520, 'Basketball (NBA)', '', 'Jokic vs Curry under the bright lights.', ''],
        ['Nuggets vs Suns', 'Ball Arena', 'Denver', '2027-01-10', 110.00, 19520, 'Basketball (NBA)', '', 'Western Conference showdown in Denver.', ''],
        ['Nuggets vs Thunder', 'Ball Arena', 'Denver', '2027-02-14', 120.00, 19520, 'Basketball (NBA)', '', 'Valentine\'s Day hoops at the altitude.', ''],
        ['Avalanche vs Red Wings', 'Ball Arena', 'Denver', '2026-10-15', 95.00, 18007, 'Hockey (NHL)', '', 'The historic rivalry continues. Bring your energy.', ''],
        ['Avalanche vs Golden Knights', 'Ball Arena', 'Denver', '2026-11-22', 110.00, 18007, 'Hockey (NHL)', '', 'Division rivalry on ice.', ''],
        ['Avalanche vs Blackhawks', 'Ball Arena', 'Denver', '2026-12-20', 85.00, 18007, 'Hockey (NHL)', '', 'Original Six vibes at Ball Arena.', ''],
        ['Avalanche vs Stars', 'Ball Arena', 'Denver', '2027-01-18', 100.00, 18007, 'Hockey (NHL)', '', 'Playoff intensity in January.', ''],
        ['Avalanche vs Wild', 'Ball Arena', 'Denver', '2027-02-28', 90.00, 18007, 'Hockey (NHL)', '', 'Central Division clash on ice.', ''],
        ['Broncos vs Chiefs', 'Empower Field at Mile High', 'Denver', '2026-09-13', 250.00, 76125, 'Football (NFL)', '', 'AFC West rivalry game. Orange crush time.', ''],
        ['Broncos vs Raiders', 'Empower Field at Mile High', 'Denver', '2026-10-04', 175.00, 76125, 'Football (NFL)', '', 'Classic divisional matchup under the lights.', ''],
        ['Broncos vs Cowboys', 'Empower Field at Mile High', 'Denver', '2026-11-01', 200.00, 76125, 'Football (NFL)', '', 'America\'s Game at Mile High.', ''],
        ['Broncos vs 49ers', 'Empower Field at Mile High', 'Denver', '2026-12-06', 185.00, 76125, 'Football (NFL)', '', 'NFC powerhouse visits Denver.', ''],
        ['Rockies vs Dodgers', 'Coors Field', 'Denver', '2026-06-15', 55.00, 50398, 'Baseball (MLB)', '', 'Rox take on LA at the best ballpark for hitters.', ''],
        ['Rockies vs Cubs', 'Coors Field', 'Denver', '2026-07-04', 65.00, 50398, 'Baseball (MLB)', '', 'Fourth of July fireworks and baseball.', ''],
        ['Rockies vs Giants', 'Coors Field', 'Denver', '2026-07-25', 45.00, 50398, 'Baseball (MLB)', '', 'NL West action at Coors Field.', ''],
        ['Rockies vs Cardinals', 'Coors Field', 'Denver', '2026-08-15', 50.00, 50398, 'Baseball (MLB)', '', 'Midwest vs Mountain clash.', ''],
        ['Rockies vs D-Backs', 'Coors Field', 'Denver', '2026-09-01', 40.00, 50398, 'Baseball (MLB)', '', 'Division rivals meet in Denver.', ''],
        ['Rapids vs LAFC', "Dick's Sporting Goods Park", 'Commerce City', '2026-06-20', 35.00, 18061, 'Soccer (MLS)', '', 'MLS action in the Mile High City.', ''],
        ['Rapids vs Sporting KC', "Dick's Sporting Goods Park", 'Commerce City', '2026-07-18', 30.00, 18061, 'Soccer (MLS)', '', 'Western Conference matchup.', ''],
        ['CU Buffs vs CSU Rams (Football)', 'Folsom Field', 'Boulder', '2026-09-05', 85.00, 50183, 'College Football', '', 'The Rocky Mountain Showdown! CU vs CSU.', ''],
        ['CU Buffs vs Nebraska (Football)', 'Folsom Field', 'Boulder', '2026-09-19', 95.00, 50183, 'College Football', '', 'The old Big 8 rivalry renewed.', ''],
        ['CU Buffs vs Utah (Football)', 'Folsom Field', 'Boulder', '2026-10-17', 75.00, 50183, 'College Football', '', 'Big 12 conference showdown in Boulder.', ''],
        ['CU Buffs Basketball vs Kansas', 'CU Events Center', 'Boulder', '2026-12-10', 60.00, 11064, 'College Basketball', '', 'Non-conference showdown with the Jayhawks.', ''],
        ['CSU Rams vs Air Force (Football)', 'Canvas Stadium', 'Fort Collins', '2026-10-03', 45.00, 36500, 'College Football', '', 'In-state college rivalry.', ''],
        ['CSU Rams vs Boise State (Football)', 'Canvas Stadium', 'Fort Collins', '2026-10-31', 50.00, 36500, 'College Football', '', 'Mountain West conference clash.', ''],
        ['DU Pioneers Hockey vs CC Tigers', 'Magness Arena', 'Denver', '2026-11-14', 35.00, 6026, 'College Hockey', '', 'NCHC rivalry on ice. Go Pios!', ''],
        ['Air Force vs Army (Football)', 'Falcon Stadium', 'Colorado Springs', '2026-11-07', 55.00, 46692, 'College Football', '', 'Commander-in-Chief\'s Trophy game.', ''],
        ['Air Force vs Navy (Football)', 'Falcon Stadium', 'Colorado Springs', '2026-10-10', 50.00, 46692, 'College Football', '', 'Service academy showdown.', ''],
    ];
}

function getGamesSeeds() {
    return [
        ['Grand Theft Auto VI', 'PlayStation 5, Xbox Series X', 'Rockstar Games', '2026-09-17', 69.99, 999999, 'Action/Adventure', '', 'Return to Vice City in the most anticipated game of the decade.', ''],
        ['The Elder Scrolls VI', 'PC, Xbox Series X', 'Bethesda', '2026-11-11', 69.99, 999999, 'RPG', '', 'The next chapter in the legendary Elder Scrolls saga.', ''],
        ['Elden Ring: Nightreign', 'PC, PS5, Xbox Series X', 'FromSoftware', '2026-06-20', 39.99, 999999, 'Action RPG', '', 'A new co-op roguelike experience in the Elden Ring universe.', ''],
        ['Hollow Knight: Silksong', 'PC, Switch, PS5, Xbox', 'Team Cherry', '2026-06-12', 29.99, 999999, 'Metroidvania', '', 'Hornet\'s long-awaited adventure finally arrives.', ''],
        ['Fable', 'PC, Xbox Series X', 'Playground Games', '2026-07-15', 69.99, 999999, 'Action RPG', '', 'A fresh reboot of the beloved Fable franchise.', ''],
        ['Civilization VII', 'PC, PS5, Xbox, Switch', 'Firaxis Games', '2026-02-11', 49.99, 999999, 'Strategy', '', 'Lead your civilization from the Stone Age to the Information Age.', ''],
        ['Metroid Prime 4: Beyond', 'Nintendo Switch 2', 'Retro Studios', '2026-08-22', 59.99, 999999, 'Action/Adventure', '', 'Samus returns in the long-awaited fourth Prime installment.', ''],
        ['Death Stranding 2', 'PS5, PC', 'Kojima Productions', '2026-06-26', 69.99, 999999, 'Action/Adventure', '', 'Hideo Kojima\'s mind-bending sequel.', ''],
        ['Monster Hunter Wilds', 'PC, PS5, Xbox Series X', 'Capcom', '2026-02-28', 59.99, 999999, 'Action RPG', '', 'Hunt massive monsters across vast open environments.', ''],
        ['DOOM: The Dark Ages', 'PC, PS5, Xbox Series X', 'id Software', '2026-05-15', 69.99, 999999, 'FPS', '', 'Rip and tear in a medieval hellscape.', ''],
        ['Hades II', 'PC, PS5, Xbox, Switch', 'Supergiant Games', '2026-07-01', 29.99, 999999, 'Roguelike', '', 'Melinoe\'s battle against the Titan of Time.', ''],
        ['Star Wars: Eclipse', 'PC, PS5, Xbox Series X', 'Quantic Dream', '2026-10-15', 69.99, 999999, 'Action/Adventure', '', 'A branching Star Wars narrative in the High Republic era.', ''],
        ['Borderlands 4', 'PC, PS5, Xbox Series X', 'Gearbox Software', '2026-09-12', 69.99, 999999, 'Looter Shooter', '', 'A bazillion more guns in a new galaxy.', ''],
        ['The Witcher IV', 'PC, PS5, Xbox Series X', 'CD Projekt Red', '2026-12-10', 69.99, 999999, 'RPG', '', 'A new saga begins in the world of The Witcher.', ''],
        ['Assassin\'s Creed Shadows', 'PC, PS5, Xbox Series X', 'Ubisoft', '2026-03-20', 69.99, 999999, 'Action RPG', '', 'Feudal Japan finally comes to Assassin\'s Creed.', ''],
        ['Ghostwire: Tokyo 2', 'PC, PS5', 'Tango Gameworks', '2026-08-08', 59.99, 999999, 'Action/Adventure', '', 'Supernatural threats return to Tokyo.', ''],
        ['Subnautica 3', 'PC, PS5, Xbox Series X', 'Unknown Worlds', '2026-07-30', 29.99, 999999, 'Survival', '', 'Dive into a brand new alien ocean world.', ''],
        ['Stardew Valley 2', 'PC, Switch', 'ConcernedApe', '2026-06-01', 19.99, 999999, 'Simulation', '', 'More farming, more fishing, more heart events.', ''],
        ['Persona 6', 'PS5, PC', 'Atlus', '2026-11-05', 59.99, 999999, 'JRPG', '', 'The next generation of Phantom Thieves.', ''],
        ['Half-Life 3', 'PC (Steam)', 'Valve', '2026-11-19', 59.99, 999999, 'FPS', '', 'It\'s actually happening. The crowbar returns.', ''],
        ['Bioshock 4', 'PC, PS5, Xbox Series X', 'Cloud Chamber', '2026-10-01', 69.99, 999999, 'FPS/RPG', '', 'Would you kindly visit a new dystopia?', ''],
        ['Pragmata', 'PC, PS5, Xbox Series X', 'Capcom', '2026-09-25', 69.99, 999999, 'Action/Adventure', '', 'A mysterious sci-fi journey from Capcom.', ''],
        ['Silica', 'PC', 'Bohemia Interactive', '2026-06-15', 29.99, 999999, 'Strategy/FPS', '', 'RTS meets FPS in an alien desert.', ''],
        ['Project Awakening', 'PS5, PC', 'Cygames', '2026-12-01', 69.99, 999999, 'Action RPG', '', 'A gorgeous new action RPG from Cygames.', ''],
        ['Avowed', 'PC, Xbox Series X', 'Obsidian Entertainment', '2026-02-18', 69.99, 999999, 'RPG', '', 'Explore the living lands of Eora.', ''],
        ['Marathon', 'PC, PS5, Xbox Series X', 'Bungie', '2026-08-15', 0.00, 999999, 'Extraction Shooter', '', 'Bungie\'s free-to-play extraction shooter.', ''],
        ['Judas', 'PC, PS5, Xbox Series X', 'Ghost Story Games', '2026-10-20', 69.99, 999999, 'FPS/RPG', '', 'From the creator of Bioshock comes a new narrative FPS.', ''],
        ['Indiana Jones and the Great Circle', 'PC, Xbox Series X, PS5', 'MachineGames', '2026-01-23', 69.99, 999999, 'Action/Adventure', '', 'Indy is back in a globe-trotting adventure.', ''],
        ['Black Myth: Wukong 2', 'PC, PS5', 'Game Science', '2026-12-15', 59.99, 999999, 'Action RPG', '', 'The Destined One returns for another mythic journey.', ''],
        ['Like a Dragon: Pirate Yakuza', 'PC, PS5, Xbox Series X', 'Ryu Ga Gotoku Studio', '2026-02-28', 59.99, 999999, 'Action RPG', '', 'Majima takes to the high seas.', ''],
    ];
}

function getCarsSeeds() {
    return [
        ['1997 Honda Civic - "The Cockroach"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 800.00, 1, 'Sedan', '', '287,000 miles. Check engine light is on but we think it\'s just lonely. Three different colored doors. AC works if you count opening the window.', ''],
        ['2001 Ford Taurus - "The Beige Nightmare"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 650.00, 1, 'Sedan', '', 'The official car of "I just need it to get to school." Transmission slips only on days ending in Y. Comes with a free air freshener (you\'ll need it).', ''],
        ['1999 Toyota Corolla - "Unkillable"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 999.00, 1, 'Sedan', '', '312,000 miles and still going. We tried to kill it. We can\'t. The bumper is held on with zip ties and prayers. Will outlive us all.', ''],
        ['2003 Pontiac Aztek - "Walter White Special"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 750.00, 1, 'SUV', '', 'Yes, it\'s that car from Breaking Bad. No, there\'s nothing in the wheel well. Probably. Don\'t look. Actually looks better in person (impossible).', ''],
        ['1998 Dodge Neon - "The Vibrating Machine"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 400.00, 1, 'Sedan', '', 'Vibrates so much at highway speed it\'ll shake loose fillings. But it starts! Most of the time. Built-in massage feature (unintentional).', ''],
        ['2002 Chevy Cavalier - "Old Faithful"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 550.00, 1, 'Sedan', '', 'Burns oil like it\'s a hobby. Leaves a smoke screen behind you for privacy. Hood doesn\'t close all the way but that\'s just character.', ''],
        ['2000 Ford Focus - "The Focus Group"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 700.00, 1, 'Hatchback', '', 'The steering wheel shakes at 45mph so you\'ll always know you\'re going 45. Rear window is garbage bag and duct tape. Gets you there (eventually).', ''],
        ['1996 Geo Metro - "The Roller Skate"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 350.00, 1, 'Subcompact', '', '3-cylinder engine that sounds like an angry sewing machine. Gets 45 MPG though. May blow away in strong wind. Recommend not taking I-70 to the mountains.', ''],
        ['2004 Chrysler PT Cruiser - "The Mistake"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 500.00, 1, 'Wagon', '', 'The car equivalent of cargo shorts. Flame decals included at no extra charge. Interior smells like 2004. Turbo model (turbo is broken).', ''],
        ['1999 Jeep Grand Cherokee - "Oil Leak Larry"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 950.00, 1, 'SUV', '', 'Marks its territory everywhere it parks. 4WD works great for getting TO the mechanic. Dashboard lights look like a Christmas tree. Still cool though.', ''],
        ['2001 Kia Rio - "Budget King"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 300.00, 1, 'Sedan', '', 'The official car of "it was this or walk." Power nothing. Manual everything. The door handle on the passenger side is decorative. Entry via window recommended.', ''],
        ['2003 Saturn Ion - "Space Junk"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 450.00, 1, 'Sedan', '', 'Made by a company that doesn\'t exist anymore, which should tell you something. Plastic body panels won\'t dent but WILL crack. Immune to rust (it\'s plastic).', ''],
        ['1997 Mercury Grand Marquis - "Grandpa\'s Revenge"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 900.00, 1, 'Sedan', '', 'Seats 47 people. Gets 12 MPG. The ultimate boat. Park it and it becomes a studio apartment. Crown Vic platform so it drives like a cop car.', ''],
        ['2000 VW Jetta - "The Money Pit"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 800.00, 1, 'Sedan', '', 'German engineering at its most affordable (the car, not the repairs). Check engine light is just the car saying "guten tag." Window regulator? What\'s that?', ''],
        ['2002 Hyundai Accent - "The Penalty Box"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 375.00, 1, 'Sedan', '', 'Penalty for what, you ask? We don\'t know either. But it runs. The paint is... conceptual. AC blows lukewarm air, which in Colorado is fine 9 months of the year.', ''],
        ['1998 Subaru Legacy - "Head Gasket Hero"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 850.00, 1, 'Wagon', '', 'Head gasket replaced (once). AWD for Colorado winters. Burns coolant like a campfire. But it\'s a Subaru so it\'s basically a Colorado resident. Sticker residue on bumper.', ''],
        ['2001 Ford Escort - "The Escort Service"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 425.00, 1, 'Sedan', '', 'Will escort you to school and back. Maybe. Name leads to awkward conversations. Timing belt is "probably fine." Heater works on one setting: surface of the sun.', ''],
        ['2003 Dodge Stratus - "Michael Scott\'s Choice"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 600.00, 1, 'Sedan', '', '"I DRIVE A DODGE STRATUS!" That\'s the best thing anyone has ever said about this car. Transmission has opinions about which gear it wants to be in.', ''],
        ['1995 Toyota Camry - "The Tan Torpedo"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 975.00, 1, 'Sedan', '', 'Every single one is beige. It\'s the law. 350,000 miles of stories. Dent in every panel tells a tale. Will run until the heat death of the universe.', ''],
        ['2000 Chevy S-10 - "The Mini Truck"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 950.00, 1, 'Truck', '', 'It\'s technically a truck. Bed is rusted through in spots (speed holes). Tailgate held on by one hinge and determination. Perfect for hauling... stuff.', ''],
        ['2002 Mitsubishi Eclipse - "Fast and Curious"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 750.00, 1, 'Sport', '', 'Looks fast standing still. Isn\'t fast moving. Previous owner added a fart-can exhaust. Spoiler adds 50hp (it doesn\'t). Pop-up headlights (just kidding, wrong gen).', ''],
        ['1999 Buick Century - "OK Boomer"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 500.00, 1, 'Sedan', '', 'Smells like butterscotch and a retired Florida vacation. Rides like a cloud. Turns like a barge. Bench seat fits your whole friend group. Trunk fits... let\'s not go there.', ''],
        ['2004 Suzuki Aerio - "The What?"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 325.00, 1, 'Wagon', '', 'Nobody remembers this car existed, including Suzuki. AWD though! Perfect for Denver. Parts availability: good luck. Wiki article: one paragraph.', ''],
        ['2001 Nissan Sentra - "The Reliable Disappointment"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 700.00, 1, 'Sedan', '', 'It\'s fine. That\'s the nicest thing anyone has said about it. Perfectly adequate transportation. Will never excite you. Will never let you down. The beige of cars.', ''],
        ['1998 Isuzu Rodeo - "The Endangered Species"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 850.00, 1, 'SUV', '', 'Isuzu left the US market and took the parts catalog with them. But it\'s got 4WD and it runs! Frame rust is just weight reduction. V6 sounds angry (it is).', ''],
        ['2003 Chevy Malibu - "The Rental"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 475.00, 1, 'Sedan', '', 'Enterprise rental fleet reject. 9 previous owners (we stopped counting). Interior has that rental car charm. Cigarette burns add character.', ''],
        ['2000 Mazda Protege - "Zoom Zoom (Not Really)"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 625.00, 1, 'Sedan', '', 'Mazda says "zoom zoom" but this one is more "putt putt." Still, it\'s surprisingly fun to drive. Or maybe that\'s the fear talking. Manual trans!', ''],
        ['1997 Oldsmobile Cutlass - "Museum Piece"', 'Lot A - Behind the Dumpster', 'Denver', '2026-04-01', 400.00, 1, 'Sedan', '', 'Oldsmobile: the car brand your grandkids won\'t believe existed. Power everything (power locks work 60% of the time). Rides like driving a couch.', ''],
        ['2004 Pontiac Grand Am - "The Campus Classic"', 'Lot C - Near the Highway', 'Lakewood', '2026-04-03', 550.00, 1, 'Sedan', '', 'The go-to car for every college campus from 2004-2010. Window motor dead in driver door. Intake manifold gasket leaks (it\'s a 3.4L, what did you expect?).', ''],
        ['2001 Ford Windstar - "The Uncool Bus"', 'Lot B - The Mud Pit', 'Aurora', '2026-04-02', 450.00, 1, 'Minivan', '', 'Sure, you wanted a sports car. But you can fit 7 friends and all your stuff. Sliding door sticks. Rear axle recall was done (probably). Ultimate road trip machine.', ''],
    ];
}
