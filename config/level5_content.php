<?php
/**
 * BULIG — Level 5 (Listening Comprehension & Vocabulary Development)
 * content, Grades 1-6.
 *
 * SOURCE: the six "LEVEL 5 LISTENING COMPREHENSION AND VOCABULARY
 * DEVELOPMENT" module booklets (Grades 1-6). Each grade's own Table of
 * Contents lists exactly 20 numbered intervention activities — those
 * titles are transcribed below EXACTLY, in the same order, for every
 * grade. Nothing here is invented, renamed, reordered, or skipped.
 *
 * IMPORTANT — HONEST STATUS OF THIS FILE:
 * Unlike Level 4's content file (fully transcribed for all six grades),
 * Level 5's source is much larger and more varied: the 6 grades are 6
 * genuinely different curricula (themed read-aloud stories for Grades
 * 1-2, illustrated short stories for Grade 3, word-skill drills — word
 * search, crossword, jumbled letters, etc. — for Grades 4-6), not one
 * format reused six times. Several activity types (Word Search,
 * Crossword, Spiral Puzzle, Link the Letters) are paper-native puzzles
 * that each need a real interaction-design decision, not a mechanical
 * copy into a quiz template.
 *
 * To avoid inventing content under time pressure, this file currently
 * ships:
 *   - The REAL, verified activity title for all 20 activities x 6 grades
 *     (bulig_level5_activity_titles()) — used everywhere so the quest
 *     map, teacher monitoring, etc. always show the true activity name,
 *     even for ones not yet playable.
 *   - FULLY transcribed, playable quiz content for Grade 1, Activities
 *     1-2 ("My Body", "My Toys") — verified word-for-word against the
 *     source PDF, including the exact vocabulary words/meanings and the
 *     exact listening-comprehension riddles with their options.
 *   - Every other activity is intentionally left out of
 *     bulig_level5_content() below. pupil/level5.php and js/level5.js
 *     both treat "no content entry" as a clearly-labeled "Content Coming
 *     Soon" node (using the REAL title) rather than a broken link or an
 *     invented placeholder quiz — see bulig_level5_activity_has_content().
 *
 * ADAPTATION NOTE (Grade 1, Activity 1 "My Body"): the source activity
 * is a physical Total-Physical-Response exercise ("point to the
 * following body parts on yourself when I say them") which a keyboard/
 * touchscreen can't verify. It's adapted here into a visual
 * identification quiz using the exact same 5 vocabulary words from the
 * source (Eye, Nose, Arm, Head, Mouth) — the pupil is shown the word and
 * picks the matching body-part icon. The words are 100% the source's;
 * only the input mechanism changed, the same way earlier levels adapted
 * "draw a line to match" into tap-to-select.
 */
declare(strict_types=1);

/**
 * The real, verified 20-activity title list for each grade, transcribed
 * directly from that grade's own Table of Contents, in the printed order.
 */
function bulig_level5_activity_titles(): array
{
    return [
        1 => [ // Grade 1 — Me & Myself / Family / School / Community
            'My Body', 'My Toys', 'My Pets', 'My Things', 'My Food',
            'My Family', 'My Relatives', 'Our Home', 'Our Tools', 'Our Kitchen',
            'In The Classroom', 'In The Garden', 'In The Canteen', 'In The Library', 'Keeping Our School Clean',
            'The Community Helpers', 'In The Plaza', 'In The Market', 'In The Farm', 'Buildings',
        ],
        2 => [ // Grade 2 — Plants / Animals / My House / My Surroundings
            'Flowers', 'Vegetables', 'Fruits', 'Trees', 'Herbs',
            'Animals live in Grassland', 'Animals live in Woodland', 'Animals live in the Meadow', 'Animals live in Forest', 'Animals live in the Seashore',
            'Things Found in the Kitchen', 'Things Found in the Living Room', 'Things Found in the Bedroom', 'Things Found in the Bathroom', 'Things Found in the Garage',
            'Land Forms', 'Body of Water', 'Mineral Resources', 'Transportation', 'Famous Location',
        ],
        3 => [ // Grade 3 — illustrated short stories
            'The Mystery of the Missing Lunchbox', 'The Magical Tree House Adventure', "The Inventor's Workshop", 'The Story of the First Thanksgiving', "The Little Seed's Journey",
            'Summer Fun', 'A Rainy Day', 'A Gift from Uncle', 'Taking Care of Animals', 'The Banana Peelings',
            'The News Boy', 'A Sick Classmate', 'The Kitten', 'The Coconut', 'Growing Vegetable',
            'A Moonlight Night', "Ramon's Cake", 'Shine', 'Fowls', 'The First Monkey',
        ],
        4 => [ // Grade 4 — word-skill drills
            'Point Act Game', 'Find and Color Me', 'Naming Me', 'Hidden Letters', 'Jumbled Letters',
            'Link the Letters', 'Spiral Puzzle', 'Crosswords', 'Opposite Words', 'Prep Paired',
            'Fill in the Blanks', 'Synonyms', 'Antonyms', 'Word Puzzle', 'Arranging Letter',
            'Correctly Spelled', 'Word Roll', 'Pair a Word', 'Be Bingo', 'Sentence Completion',
        ],
        5 => [ // Grade 5 — vocabulary + comprehension skill activities
            'Multiple Choice Vocabulary Review', 'Fill in the Blank Puzzle', 'Short Answer and Categorization', 'Definitions Matching', 'Matching Activity: Vocabulary Definitions',
            'Fill in the Blank Definitions', 'Multiple Choice Questions', 'Match the Definitions', 'Crossword Puzzle', 'True or False',
            'Short Answer Questions', 'Create a Sentence', 'Vocabulary Riddles', 'Sorting Activity', 'Word Search',
            'Vocabulary Riddles', 'Word Search', 'Fill in the Blank Definitions', 'Fill in the Blanks', 'Multiple Choice',
        ],
        6 => [ // Grade 6 — word-skill drills
            'Act Game', 'Match and Color', 'Word Search', 'Hidden Letters', 'Jumbled Letters',
            'Link the Letters', 'Spin Act', 'Crossword', 'Opposite Words', 'Prep Paired',
            'Fill in the Blank', 'Synonyms', 'Replace a Word', 'Word Puzzle', 'Arrange a Word',
            'Correctly Spelled', 'Roll Word Game', 'Word Match', 'Sentence Complete', 'Paragraph Filling',
        ],
    ];
}

/** Every grade currently has exactly 20 real, verified intervention activities. */
function bulig_level5_activity_count(int $grade): int
{
    $titles = bulig_level5_activity_titles();
    return isset($titles[$grade]) ? count($titles[$grade]) : 0;
}

/**
 * Fully transcribed, playable quiz content. Keyed [grade][activityNumber].
 * Every item is a plain multiple-choice question: ['q' => ..., 'options'
 * => [4 strings], 'correct' => zero-based index]. Vocabulary-matching
 * items are represented the same way ("What does X mean?" / "Which
 * picture shows X?") so a single quiz engine can run any of them.
 */
function bulig_level5_content(): array
{
    return [
        1 => [ // Grade 1
            1 => [ // "My Body" — adapted from the source's point-to-body-part exercise (see note above)
                'intro' => "Let's learn the names of our body parts!",
                'questions' => [
                    ['q' => "Which one is your EYE?",  'options' => ['👁️ Eye', '👃 Nose', '👄 Mouth', '💪 Arm'], 'correct' => 0],
                    ['q' => "Which one is your NOSE?", 'options' => ['👄 Mouth', '👃 Nose', '💪 Arm', '👁️ Eye'], 'correct' => 1],
                    ['q' => "Which one is your ARM?",  'options' => ['👁️ Eye', '👄 Mouth', '💪 Arm', '👃 Nose'], 'correct' => 2],
                    ['q' => "Which one is your MOUTH?",'options' => ['👄 Mouth', '💪 Arm', '👁️ Eye', '👃 Nose'], 'correct' => 0],
                    ['q' => "Point to your HEAD! Which word names your head?", 'options' => ['Nose', 'Head', 'Arm', 'Eye'], 'correct' => 1],
                ],
            ],
            2 => [ // "My Toys" — fully verified word-for-word from the source
                'intro' => "These are my toys. My mother and father give it to me! I can name my favorite toys.",
                'questions' => [
                    // A. Vocabulary Assessment (word -> meaning)
                    ['q' => "What does 'Ball' mean?",      'options' => ['A toy with wheels you can ride', 'A round toy you can throw and catch', 'A toy that looks like a person', 'A soft toy you can hug'], 'correct' => 1],
                    ['q' => "What does 'Doll' mean?",      'options' => ['A toy that looks like a person', 'A toy that can fly in the wind', 'A round toy you can throw and catch', 'A soft toy you can hug'], 'correct' => 0],
                    ['q' => "What does 'Kite' mean?",      'options' => ['A toy with wheels you can ride', 'A soft toy you can hug', 'A toy that can fly in the wind', 'A toy that looks like a person'], 'correct' => 2],
                    ['q' => "What does 'Car' mean?",       'options' => ['A toy with wheels you can ride', 'A round toy you can throw and catch', 'A toy that can fly in the wind', 'A soft toy you can hug'], 'correct' => 0],
                    ['q' => "What does 'Teddy Bear' mean?",'options' => ['A toy that looks like a person', 'A toy that can fly in the wind', 'A round toy you can throw and catch', 'A soft toy you can hug'], 'correct' => 3],
                    // B. Listening Comprehension riddles (verbatim from source)
                    ['q' => "\u201cMy parents gave me something I can throw and catch. It\u2019s round and bouncy. What is it?\u201d", 'options' => ['Kite', 'Ball', 'Doll', 'Teddy Bear'], 'correct' => 1],
                    ['q' => "\u201cI love to play with my toy on the water. It has a sail and helps me pretend I\u2019m a sailor. What am I playing with?\u201d", 'options' => ['Car', 'Train', 'Boat', 'Yoyo'], 'correct' => 2],
                    ['q' => "\u201cI have a toy that goes \u2018choo-choo\u2019 and runs on tracks. What toy is this?\u201d", 'options' => ['Car', 'Train', 'Kite', 'Yoyo'], 'correct' => 1],
                    ['q' => "\u201cThis toy is soft and furry, and I love to cuddle with it when I sleep. Which toy is it?\u201d", 'options' => ['Ball', 'Doll', 'Teddy Bear', 'Kite'], 'correct' => 2],
                    ['q' => "\u201cI have a toy that I can make go up in the sky when the wind blows. What is it?\u201d", 'options' => ['Car', 'Yoyo', 'Kite', 'Doll'], 'correct' => 2],
                ],
            ],
        ],
        // Grades 2-6: no entries yet -> shown as "Content Coming Soon" with their real titles.
    ];
}

/** True only for an activity that actually has playable quiz content above. */
function bulig_level5_activity_has_content(int $grade, int $activityNum): bool
{
    $content = bulig_level5_content();
    return isset($content[$grade][$activityNum]);
}
