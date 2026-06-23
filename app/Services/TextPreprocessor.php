<?php

namespace App\Services;

class TextPreprocessor
{
    /**
     * Main pipeline method to execute the full NLP text preprocessing.
     * Applies case folding, cleansing, tokenizing, normalization, stopword removal, and stemming.
     *
     * @param string $original The raw input text
     * @return string The fully processed text
     */
    public function processText($original)
    {
        // ======================
        // COMPREHENSIVE STOPWORDS
        // ======================
        $stopwords = [
            // Basic Indonesian stopwords
            'dan', 'atau', 'serta', 'dari', 'ke', 'di', 'pada', 'untuk', 'buat', 'yang', 'bahwa',
            'kalau', 'jika', 'bila', 'tapi', 'tetapi', 'namun', 'melainkan', 'karena', 'sebab',
            'jadi', 'sehingga', 'oleh', 'dengan', 'sambil', 'seperti', 'setelah', 'sebelum',
            'selama', 'sejak', 'sebelumnya', 'setelahnya', 'selanjutnya', 'hingga',
            
            // Pronouns
            'saya', 'aku', 'kita', 'kami', 'anda', 'dia', 'mereka', 'beliau', 'engkau', 'dikau',
            'gue', 'gw', 'gua', 'lo', 'lu', 'kau', 'kalian', 'kamu', 'anda', 'beliau',
            
            // Demonstratives
            'ini', 'itu', 'tersebut', 'begini', 'begitu', 'gini', 'gitu', 'gt', 'sana', 'sini', 'situ',
            'kesana', 'kesini', 'disana', 'disini', 'disitu', 'begitulah', 'gitulah',
            
            // Question words
            'apa', 'apakah', 'kenapa', 'mengapa', 'napa', 'bagaimana', 'gimana', 'gmn',
            'kapan', 'dimana', 'dimanapun', 'kemana', 'mana', 'siapa', 'berapa', 'kpn', 'knp',
            'apasih', 'apaan', 'ngapain', 'apalagi', 'apalag', 'kena', 'kenap',
            
            // ALL interjections and noise words - HAPUS TOTAL
            'hmm', 'hmmm', 'wkwk', 'wkwkwk', 'wkwkwkwk', 'wkwkwkwkwk', 'wkwwk', 'wkwkk',
            'hahaha', 'hihi', 'hehe', 'hehehe', 'hadeh', 'hadeuh', 'halah', 'waduh',
            'gilee', 'woy', 'woyy', 'woi', 'bro', 'gan', 'om', 'tante', 'mas', 'mbak', 'dek',
            'nih', 'tuh', 'sih', 'dih', 'yah', 'ya', 'yaa', 'yaah', 'anjay', 'njir', 'njirr', 'njirrr',
            'astaga', 'telek', 'taek', 'cuihh', 'cuih', 'gpp', 'ayo', 'ayok', 'yuk', 
            'tolong', 'please', 'plis', 'pliss', 'nah', 'lah', 'lha', 'lho', 'loh', 'dong', 
            'deh', 'kok', 'weh', 'duh', 'ah', 'oh', 'eh', 'kan', 'pun', 'kah', 'tah',
            'ciee', 'cie', 'waw', 'wkwkwkwwk', 'hehehhe', 'hahahaha', 'wkwkwkwk',
            'mah', 'sih', 'deh', 'dong', 'kok', 'lho', 'loh', 'kan', 'pun', 'nya', 'nye',
            'weh', 'woi', 'woy', 'bro', 'gan', 'om', 'tante', 'mbak', 'dek', 'kak',
            'yahc', 'yee', 'yukk', 'ahh', 'ohh', 'ehh', 'woii', 'woiii', 'woyyy',
            'gtu', 'gtu2', 'gitu2', 'gitu2an', 'gtu2an', 'gini2', 'gini2an',
            'aduh', 'astaga', 'astaghfirul', 'astaghfirullah', 'suudzon',
            'naanti', 'naant', 'naantii', 'brrti', 'brrt', 'brrtii', 'nga', 'dika', 'dita',
            
            // Conjunctions and connectives
            'sehingga', 'karena', 'krn', 'sebab', 'karna', 'tetapi', 'tp', 'tpi', 
            'namun', 'sedangkan', 'sementara', 'melainkan', 'seperti', 'spt', 'kayak', 
            'kyk', 'kek', 'contoh', 'misal', 'misalnya', 'lagipula', 'lagian', 
            'biarpun', 'meskipun', 'walaupun', 'kalo', 'klo', 'kalau', 'kalu', 
            'soal', 'soalnya', 'soaln', 'bisaa', 'ntr', 'ntar', 'nnti', 'ntu', 
            'ntah', 'entah', 'apapun', 'siapapun', 'manapun', 'kapanpun',
            
            // Quantitative
            'banyak', 'byk', 'sedikit', 'dikit', 'beberapa', 'bbrp', 'semua', 'seluruh',
            'setiap', 'tiap', 'masing', 'masing2', 'semuanya', 'segala', 'segenap',
            'cuman', 'cuma', 'hanya', 'sekadar', 'sekedar', 'doang', 'aja', 'saja', 'aj', 'ajah',
            
            // Temporal
            'sekarang', 'skrg', 'dulu', 'kemarin', 'kmren', 'maren', 'besok', 'hari', 'bulan',
            'tahun', 'setahun', 'setaun', 'taun', 'tahunan', 'bulanan', 'harian', 'minggu',
            'sehari', 'sebentar', 'ntar', 'nanti', 'nnti', 'saat', 'ketika', 'hingga',
            'lalu', 'dahulu', 'kemudian', 'mudi', 'kemudia', 'sebelum', 'sesudah', 'sete',
            'dah', 'uda', 'udah', 'sdh', 'udh', 'dah', 'belum', 'blm', 'blum',
            
            // Spatial
            'atas', 'bawah', 'depan', 'belakang', 'samping', 'dalam', 'luar', 'antara',
            'sekitar', 'seberang', 'sebelah', 'tepi', 'pinggir',
            
            // Manner
            'secara', 'dengan', 'tanpa', 'melalui', 'lewat', 'via', 'pakai', 'pake',
            'guna', 'menggunakan', 'memakai', 'melakukan', 'melaksanakan',
            
            // Abbreviations - HAPUS
            'dll', 'dsb', 'dst', 'dkk', 'btw', 'aka', 'alias', 'dllnya', 'dll.', 'dll2',
            
            // Common verbs (low sentiment value)
            'ada', 'pernah', 'akan', 'bisa', 'dapat', 'boleh', 'harus', 'wajib',
            'mampu', 'mau', 'ingin', 'tentu', 'pastinya', 'dpt', 'bs', 'bisa',
            'merupakan', 'adalah', 'ialah', 'yakni', 'yaitu', 'yak', 'yakn',
            'merupak', 'mrupak', 'adlh', 'ialh',
            
            // Greetings/closings
            'assalamualaikum', 'selamat', 'pagi', 'siang', 'sore', 'malam',
            'hai', 'hello', 'halo', 'hi', 'salam', 'wasalam', 'bye', 'dadah',
            'makasih', 'terima kasih', 'thanks', 'thankyou', 'thank', 'thx',
            
            // Discourse markers
            'masa', 'mosok', 'ora', 'ra', 'blas', 'nek', 'iso', 'iki', 'iku',
            'nde', 'rasanya', 'kayanya', 'sepertinya', 'mungkin',
            'intinya', 'pokoknya', 'coba', 'mari', 'dikira', 'kirain',
            'rasa', 'rasan', 'rasanya', 'kayaknya', 'kayakny', 'kyknya',
            
            // Particles - HAPUS
            'lah', 'lha', 'lho', 'loh', 'dong', 'deh', 'sih', 'kok', 'kan', 'pun',
            'mah', 'nya', 'ko', 'weh', 'doang', 'aja', 'saja', 'bang', 'bgt',
            'aj', 'ajah', 'dah', 'udah', 'uda', 'sdh', 'udh', 'gpp', 'gausah',
            'yah', 'ya', 'yee', 'iyo', 'iyoo', 'iya', 'iy', 'ngga', 'gga',
            
            // Shouting/exclamation words (Removed positive words from here)
            'yes', 'yess', 'yesss',
            
            // Patterns
            'wkwkwkwk', 'wkwkwk', 'wkwk', 'wkwwk', 'wkwkk', 'hahaha', 'hehehe',
            'hihi', 'hadeh', 'hadeuh', 'halah', 'waduh', 'anjay', 'njir', 'njirr',
            'cie', 'ciee', 'waw', 'gilee', 'astaga', 'telek', 'taek', 'cuih', 'cuihh',
            'aduuuh', 'aduh', 'yael', 'yaelah', 'yaeloh', 'halah', 'wehh', 'weehh',
            'ajik', 'ajikn', 'ajikannya',
            
            // Additional stopwords
            'hrs', 'harus', 'wajib', 'dalam', 'dlm', 'dalzm', 'dalem',
            'bsa', 'bisa', 'dpt', 'dapat',
        ];

        $text = $this->handleCaseFolding($original);
        $text = $this->handleCleansing($text);
        $tokens = $this->handleTokenizing($text);
        $tokens = $this->handleNormalization($tokens);

        $tokens = array_filter($tokens, function ($word) use ($stopwords) {
            $negationWords = ['tidak', 'belum', 'jangan', 'bukan', 'tak', 'takkan', 
                              'takpernah', 'enggak', 'nggak', 'ga', 'gak', 'tidak usah', 
                              'tidak mau', 'pasti', 'palak', 'kasih', 'minta', 'tanya',
                              'berantas', 'basmi', 'cari', 'rampok', 'suruh', 'dishub',
                              'kampung', 'tertib', 'ide', 'hitung', 'peras', 'masuk',
                              'retribusi', 'jamin', 'lebih', 'transparan', 'tolak'];
            
            if (in_array($word, $negationWords)) {
                return true;
            }
            
            if (in_array($word, $stopwords)) {
                return false;
            }
            
            if (strlen($word) <= 2 && !in_array($word, $negationWords)) {
                return false;
            }
            
            return true;
        });

        $finalTokens = $this->handleStemming($tokens);
        $finalTokens = array_filter($finalTokens);
        
        return implode(' ', $finalTokens);
    }

    /**
     * Analyze a text word by word to show preprocessing breakdown
     *
     * @param string $original The raw input text
     * @return array Array of analysis details for each word
     */
    public function analyzeText($original)
    {
        $words = explode(' ', $original);
        $analysis = [];
        
        foreach ($words as $word) {
            $word = trim($word);
            if ($word === '') continue;
            
            $processed = $this->processText($word);
            
            $analysis[] = [
                'original' => $word,
                'processed' => empty(trim($processed)) ? '-' : $processed,
            ];
        }
        
        return $analysis;
    }

    /**
     * Convert all characters in the text to lowercase (Case Folding)
     *
     * @param string $text
     * @return string
     */
    public function handleCaseFolding($text)
    {
        return mb_strtolower($text);
    }

    /**
     * Clean the text by removing URLs, mentions, hashtags, numbers, punctuation, 
     * and applying spelling corrections.
     *
     * @param string $text
     * @return string
     */
    public function handleCleansing($text)
    {
        // Remove URLs
        $text = preg_replace('/https?:\/\/\S+|www\.\S+/i', '', $text);
        
        // Remove mentions
        $text = preg_replace('/@\w+/', '', $text);
        
        // Remove hashtags
        $text = preg_replace('/#/', '', $text);
        
        // Remove numbers
        $text = preg_replace('/[0-9]+/', '', $text);
        
        // Replace punctuation with spaces
        $text = preg_replace('/\.{2,}/', ' ', $text);
        $text = preg_replace('/\./', ' ', $text);
        $text = preg_replace('/\!+/', ' ', $text);
        $text = preg_replace('/\?+/', ' ', $text);
        $text = preg_replace('/,+/', ' ', $text);
        $text = preg_replace('/[;:]+/', ' ', $text);
        $text = preg_replace('/[-_]+/', ' ', $text);
        $text = preg_replace('/[\/\\\\]+/', ' ', $text);
        $text = preg_replace('/[\(\)\[\]\{\}<>]+/', ' ', $text);
        $text = preg_replace('/&+/', ' ', $text);
        $text = preg_replace('/\++/', ' ', $text);
        $text = preg_replace('/=+/', ' ', $text);
        $text = preg_replace('/\*+/', ' ', $text);
        
        // Remove remaining non-letter, non-space characters
        $text = preg_replace('/[^a-z\s]/', '', $text);
        
        // ======================
        // FIX SPELLING ERRORS
        // ======================
        $text = preg_replace('/\bnaanti+\b/', 'nanti', $text);
        $text = preg_replace('/\bbrrti+\b/', 'berarti', $text);
        $text = preg_replace('/\bkorddinir+\b/', 'koordinir', $text);
        $text = preg_replace('/\bdipalakin+\b/', 'palak', $text);
        $text = preg_replace('/\bdimintain+\b/', 'minta', $text);
        $text = preg_replace('/\bdisajikan+\b/', 'sajikan', $text);
        $text = preg_replace('/\bngasih+\b/', 'kasih', $text);
        $text = preg_replace('/\bnyari+\b/', 'cari', $text);
        $text = preg_replace('/\bnyar+\b/', 'cari', $text);
        $text = preg_replace('/\bperampokan+\b/', 'rampok', $text);
        $text = preg_replace('/\bdisuruh+\b/', 'suruh', $text);
        $text = preg_replace('/\bdishuub+\b/', 'dishub', $text);
        $text = preg_replace('/\bhuub+\b/', 'dishub', $text);
        $text = preg_replace('/\bdikampung+\b/', 'kampung', $text);
        $text = preg_replace('/\bkampung+\b/', 'kampung', $text);
        $text = preg_replace('/\bampung+\b/', 'kampung', $text);
        $text = preg_replace('/\bhrs+\b/', '', $text);
        $text = preg_replace('/\bdalzm+\b/', '', $text);
        $text = preg_replace('/\bbrantas+\b/', 'berantas', $text);
        $text = preg_replace('/\bnertibin+\b/', 'tertib', $text);
        $text = preg_replace('/\bngide+\b/', 'ide', $text);
        $text = preg_replace('/\bitungann?y?a?\b/', 'hitung', $text);
        $text = preg_replace('/\bdiperas+\b/', 'peras', $text);
        
        // ======================
        // NEW FIXES
        // ======================
        $text = preg_replace('/\btermasuk+\b/', 'masuk', $text);
        $text = preg_replace('/\bmnjamin+\b/', 'jamin', $text);
        $text = preg_replace('/\bbsa+\b/', '', $text);
        $text = preg_replace('/\blbh+\b/', 'lebih', $text);
        $text = preg_replace('/\btranparan+\b/', 'transparan', $text);
        $text = preg_replace('/\btranparansi+\b/', 'transparan', $text);
        
        // Fix T O L A K pattern (spaces between letters)
        $text = preg_replace('/\bt\s+o\s+l\s+a\s+k\b/', 'tolak', $text);
        
        // Handle repeated characters (elongation)
        $text = preg_replace('/([aiueo])\1{3,}/', '$1$1', $text);
        $text = preg_replace('/([bcdfghjklmnpqrstvwxyz])\1{2,}/', '$1', $text);
        
        // Fix specific elongation cases
        $text = preg_replace('/\bter+o+s+\b/', 'terus', $text);
        $text = preg_replace('/\bter+o+o+o+s+\b/', 'terus', $text);
        $text = preg_replace('/\bbang+e+t+\b/', 'banget', $text);
        $text = preg_replace('/\bsamp+ai+\b/', 'sampai', $text);
        $text = preg_replace('/\bad+aa+\b/', 'ada', $text);
        $text = preg_replace('/\bpast+i+\b/', 'pasti', $text);
        
        // Remove all wkwk and similar patterns
        $text = preg_replace('/\bw*k+w*k+\b/', ' ', $text);
        $text = preg_replace('/\b(h+|w+)(a+|e+|i+|o+|u+)(h+|w+)\b/', ' ', $text);
        
        // Normalize spaces
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        return $text;
    }

    /**
     * Tokenize the text into an array of words (Tokenization)
     * Also splits attached words and handles specific slang patterns.
     *
     * @param string $text
     * @return array
     */
    public function handleTokenizing($text)
    {
        $tokens = explode(' ', $text);
        $tokens = array_filter($tokens, function($token) {
            return $token !== '' && $token !== ' ';
        });
        
        // Split attached words
        $expanded = [];
        foreach ($tokens as $token) {
            // Check if token contains space (from fixed override)
            if (strpos($token, ' ') !== false) {
                $parts = explode(' ', $token);
                foreach ($parts as $part) {
                    if ($part !== '') {
                        $expanded[] = $part;
                    }
                }
                continue;
            }
            
            // Fix: arkir -> parkir
            if ($token === 'arkir' || $token === 'arkirnya') {
                $expanded[] = 'parkir';
                continue;
            }
            
            // Fix: alakin -> palak
            if ($token === 'alakin') {
                $expanded[] = 'palak';
                continue;
            }
            
            // Fix: mintain -> minta
            if ($token === 'mintain') {
                $expanded[] = 'minta';
                continue;
            }
            
            // Fix: nyar / nyari -> cari
            if ($token === 'nyar' || $token === 'nyari') {
                $expanded[] = 'cari';
                continue;
            }
            
            // Fix: ampo -> rampok
            if ($token === 'ampo') {
                $expanded[] = 'rampok';
                continue;
            }
            
            // Fix: uruh -> suruh
            if ($token === 'uruh') {
                $expanded[] = 'suruh';
                continue;
            }
            
            // Fix: huub -> dishub
            if ($token === 'huub') {
                $expanded[] = 'dishub';
                continue;
            }
            
            // Fix: brantas -> berantas
            if ($token === 'brantas') {
                $expanded[] = 'berantas';
                continue;
            }
            
            // Fix: nertibin -> tertib
            if ($token === 'nertibin') {
                $expanded[] = 'tertib';
                continue;
            }
            
            // Fix: ngide -> ide
            if ($token === 'ngide') {
                $expanded[] = 'ide';
                continue;
            }
            
            // Fix: diperas -> peras
            if ($token === 'diperas') {
                $expanded[] = 'peras';
                continue;
            }
            
            // Fix: mnjamin -> jamin
            if ($token === 'mnjamin') {
                $expanded[] = 'jamin';
                continue;
            }
            
            // Fix: bsa -> remove
            if ($token === 'bsa') {
                continue;
            }
            
            // Fix: lbh -> lebih
            if ($token === 'lbh') {
                $expanded[] = 'lebih';
                continue;
            }
            
            // Fix: hrs -> remove
            if ($token === 'hrs') {
                continue;
            }
            
            // Fix: dalzm -> remove
            if ($token === 'dalzm') {
                continue;
            }
            
            // Pattern: word + nya + word (hukumnyabacok -> hukum + bacok)
            if (preg_match('/^(.+?)(nya)(.+)$/u', $token, $matches)) {
                $expanded[] = $matches[1];
                $expanded[] = $matches[3];
                continue;
            }
            
            // Pattern: aja + word
            if (strpos($token, 'aja') === 0 && strlen($token) > 3) {
                $expanded[] = 'aja';
                $expanded[] = substr($token, 3);
                continue;
            }
            
            // Pattern: word + aja
            if (substr($token, -3) === 'aja' && strlen($token) > 3) {
                $expanded[] = substr($token, 0, -3);
                $expanded[] = 'aja';
                continue;
            }
            
            // Pattern: gass + word
            if (strpos($token, 'gass') === 0 && strlen($token) > 4) {
                $expanded[] = 'gass';
                $expanded[] = substr($token, 4);
                continue;
            }
            
            // Pattern: word + gass
            if (substr($token, -4) === 'gass' && strlen($token) > 4) {
                $expanded[] = substr($token, 0, -4);
                $expanded[] = 'gass';
                continue;
            }
            
            // Pattern: word + pun
            if (substr($token, -3) === 'pun' && strlen($token) > 4) {
                $expanded[] = substr($token, 0, -3);
                continue;
            }
            
            // Skip reversed words
            if ($token === 'ueg' || $token === 'u eg') {
                continue;
            }
            
            $expanded[] = $token;
        }
        
        return array_values(array_filter($expanded));
    }

    /**
     * Normalize slang, abbreviations, and informal language to their standard Indonesian equivalents.
     *
     * @param array $tokens
     * @return array
     */
    public function handleNormalization($tokens)
    {
        $normalizationMap = [
            // Negation variants
            'gak' => 'tidak', 'ga' => 'tidak', 'nggak' => 'tidak', 'gk' => 'tidak', 
            'tdk' => 'tidak', 'engga' => 'tidak', 'kagak' => 'tidak', 'gaada' => 'tidak ada',
            'gada' => 'tidak ada', 'ngga' => 'tidak', 'kgk' => 'tidak', 'kaga' => 'tidak',
            'gakada' => 'tidak ada', 'gkada' => 'tidak ada',
            
            // Common particles
            'drpd' => 'daripada', 'jg' => 'juga', 'bgt' => 'banget', 'udh' => 'sudah',
            'udah' => 'sudah', 'sdh' => 'sudah', 'uda' => 'sudah', 'dah' => 'sudah',
            'org' => 'orang', 'mending' => 'mending', 'mendingan' => 'mending',
            'bg' => 'bang',
            
            // Time related
            'taun' => 'tahun', 'setaun' => 'setahun', 'th' => 'tahun',
            'kmren' => 'kemarin', 'maren' => 'kemarin', 'kmrin' => 'kemarin',
            'sampe' => 'sampai', 'ntar' => 'nanti', 'nnti' => 'nanti',
            'naanti' => 'nanti', 'naant' => 'nanti',
            
            // Fix misspellings
            'brrti' => 'berarti', 'brrt' => 'berarti', 'brrtii' => 'berarti',
            'korddinir' => 'koordinir',
            'past' => 'pasti',
            
            // Fix verbs
            'dipalakin' => 'palak',
            'dipalak' => 'palak',
            'alakin' => 'palak',
            'palakin' => 'palak',
            'dimintain' => 'minta',
            'mintain' => 'minta',
            'ngasih' => 'kasih',
            'ngasi' => 'kasih',
            'dikasih' => 'kasih',
            'dika' => 'kasih',
            'disajikan' => 'sajikan',
            'ajik' => 'sajikan',
            'ajikn' => 'sajikan',
            'ditanya' => 'tanya',
            'dita' => 'tanya',
            
            // Fix for nyari, perampokan, disuruh, dishuub
            'nyari' => 'cari',
            'nyar' => 'cari',
            'mencari' => 'cari',
            'dicari' => 'cari',
            'perampokan' => 'rampok',
            'merampok' => 'rampok',
            'dirampok' => 'rampok',
            'ampo' => 'rampok',
            'disuruh' => 'suruh',
            'uruh' => 'suruh',
            'menyuruh' => 'suruh',
            'dishuub' => 'dishub',
            'huub' => 'dishub',
            
            // New fixes for kampung, berantas, etc
            'ampung' => 'kampung',
            'dikampung' => 'kampung',
            'kampung' => 'kampung',
            'hrs' => '',
            'dalzm' => '',
            'brantas' => 'berantas',
            'nertibin' => 'tertib',
            'ngide' => 'ide',
            'itungannya' => 'hitung',
            'itungan' => 'hitung',
            'diperas' => 'peras',
            
            // ======================
            // NEW FIXES
            // ======================
            'termasuk' => 'masuk',
            'mnjamin' => 'jamin',
            'bsa' => '',
            'lbh' => 'lebih',
            'tranparan' => 'transparan',
            'tranparansi' => 'transparan',
            'tolak' => 'tolak',
            't o l a k' => 'tolak',
            
            // Normalize "berantas" variants
            'memberantas' => 'berantas',
            'diberantas' => 'berantas',
            'pemberantasan' => 'berantas',
            'membasmi' => 'basmi',
            'dibasmi' => 'basmi',
            'pembasmian' => 'basmi',
            
            // Slang to formal
            'klo' => 'kalau', 'kalo' => 'kalau', 'kalu' => 'kalau',
            'kyk' => 'seperti', 'kek' => 'seperti', 'kaya' => 'seperti',
            'gitu' => 'begitu', 'gt' => 'begitu', 'gitulah' => 'begitu',
            'banget' => 'banget', 'bener' => 'benar', 'bner' => 'benar',
            'anj' => 'anjing', 'anjg' => 'anjing', 'ajg' => 'anjing',
            
            // Profanity
            'anjing' => 'anjing', 'anjir' => 'anjing', 'bangsat' => 'bangsat',
            'tolol' => 'tolol', 'goblok' => 'goblok', 'bodoh' => 'bodoh',
            'kontol' => 'kontol', 'memek' => 'memek', 'ngentot' => 'ngentot',
            'brengsek' => 'brengsek', 'bajingan' => 'bajingan',
            
            // Personal pronouns
            'gua' => 'saya', 'gue' => 'saya', 'gw' => 'saya', 'aku' => 'saya',
            'lo' => 'kamu', 'lu' => 'kamu', 'kau' => 'kamu', 'anda' => 'kamu',
            'kita' => 'kami',
            
            // Common verbs
            'bisa' => 'bisa', 'dapat' => 'dapat', 'boleh' => 'boleh',
            'harus' => 'harus', 'wajib' => 'wajib', 'mampu' => 'mampu',
            'mau' => 'mau', 'ingin' => 'ingin', 'semoga' => 'semoga',
            'kasih' => 'kasih', 'minta' => 'minta', 'tanya' => 'tanya', 'palak' => 'palak',
            
            // Fix for "ajamending"
            'ajamending' => 'mending',
            'ajamendingan' => 'mending',
            'ajamendingin' => 'mending',
            'mendingaja' => 'mending',
            
            // Positive Slang
            'mantaap' => 'mantap', 'mantabb' => 'mantap', 'mantab' => 'mantap', 'mantapp' => 'mantap',
            'kerenn' => 'keren', 'kerennn' => 'keren', 'nihh' => 'nih',
            'ok' => 'oke', 'okey' => 'oke', 'okelah' => 'oke', 'tetep' => 'tetap',
            
            // Fix for "gass"
            'gass' => 'gas',
            'gasss' => 'gas',
            'gassco' => 'gas',
            
            // Fix for "gausah", "gasah"
            'gausah' => 'tidak usah',
            'gasah' => 'tidak usah',
            'gausahh' => 'tidak usah',
            'gausahhh' => 'tidak usah',
            
            // Fix for "gamau"
            'gamau' => 'tidak mau',
            'gamauu' => 'tidak mau',
            'gamauuu' => 'tidak mau',
            'gamauuuu' => 'tidak mau',
            
            // Fix for "wkwk" etc - remove
            'wkwk' => '', 'wkwkwk' => '', 'wkwkwkwk' => '',
            'wkwwk' => '', 'wkwkk' => '',
            'hahaha' => '', 'hehehe' => '', 'hihi' => '',
            'hehehhe' => '', 'hahahaha' => '',
            
            // Fix for "ciee", "cie"
            'ciee' => '', 'cie' => '',
            
            // Fix for "waw"
            'waw' => '', 'wow' => '',
            
            // Fix for "gilee"
            'gilee' => '',
            
            // Fix for "njir", "njirr"
            'njir' => 'anjing', 'njirr' => 'anjing',
            
            // Fix for "anjay"
            'anjay' => 'anjing',
            
            // Fix for "ueg"
            'ueg' => '', 'u eg' => '',
            
            // Abbreviations
            'dll' => '', 'dsb' => '', 'dst' => '', 'dkk' => '', 'btw' => '', 'aka' => '',
        ];
        
        $result = [];
        foreach ($tokens as $token) {
            if (isset($normalizationMap[$token])) {
                $replacement = $normalizationMap[$token];
                if ($replacement !== '') {
                    if (strpos($replacement, ' ') !== false) {
                        $parts = explode(' ', $replacement);
                        $result = array_merge($result, $parts);
                    } else {
                        $result[] = $replacement;
                    }
                }
            } else {
                $result[] = $token;
            }
        }
        
        return array_filter($result);
    }

    /**
     * Retrieve a list of words that should be protected from stemming and stopword removal.
     * These include important entities, locations, sentiments, and government terms.
     *
     * @return array
     */
    public function getProtectedWords()
    {
        return [
            // Government institutions
            'dishub', 'dinas', 'pemda', 'pemkot', 'pemkab', 'pemprov', 'pemerintah',
            'dprd', 'dpr', 'dprri', 'dpd', 'mpu', 'bpk', 'bpkp', 'kpk', 'polri', 'tni',
            'kemenkeu', 'kemendagri', 'kemenhub', 'bumn', 'bumd', 'bpjs', 'asn', 'polisi',
            
            // Financial/Policy terms
            'apbn', 'apbd', 'pkb', 'swdkllj', 'ppn', 'pph', 'pbb', 'opsen',
            'stnk', 'samsat', 'sim', 'ktp', 'npwp', 'nib', 'siup', 'tdp',
            
            // Locations
            'makassar', 'surabaya', 'jakarta', 'bandung', 'semarang', 'medan',
            'jogja', 'yogyakarta', 'solo', 'surakarta', 'kediri', 'jateng',
            'jatim', 'jabar', 'banten', 'sumut', 'sumbar', 'riau', 'kaltim',
            'konoha', 'indonesia', 'malaysia', 'singapura', 'london', 'depok',
            'cimahi', 'bandung', 'sidoarjo', 'malang', 'probolinggo', 'jombang',
            'kampung',
            
            // Parking/Transport specific
            'parkir', 'pajak', 'motor', 'mobil', 'kendaraan', 'truk', 'bus', 'angkot',
            'tukang', 'preman', 'ormas', 'akamsi', 'parcok', 'jukir',
            
            // Names/calls
            'kang', 'bang', 'pak', 'bu', 'mas', 'mbak', 'dek', 'kak', 'lur', 'bro',
            'gacoan', 'indomaret', 'alfamart', 'indomart', 'samsat', 'gaco', 'mending',
            
            // Sentiment keywords
            'korupsi', 'pungli', 'malak', 'palak', 'setor', 'pungut', 'kena',
            'setuju', 'tolak', 'protes', 'demo', 'dukung', 'ogah', 'benci', 'suka',
            'marah', 'kecewa', 'senang', 'bangga', 'puas', 'kesal', 'capek', 'males',
            'rugi', 'untung', 'gratis', 'berbayar', 'langganan', 'tahunan', 'bulanan',
            'rapih', 'getol', 'bener', 'salah', 'parah', 'kacau', 'aneh', 'bet',
            'ngawur', 'nyeleneh', 'gila', 'tolol', 'goblok', 'bodoh', 'bego',
            'solusi', 'brilian', 'cerdas', 'pinter', 'bijak', 'tegas',
            'baik', 'bagus', 'oke', 'mantap', 'top', 'keren', 'sepakat', 'gas', 'aman',
            'menguntungkan', 'asuransi', 'hilang',
            
            // Words related to "berantas" - KEEP
            'berantas', 'basmi',
            
            // Important verbs
            'cari', 'rampok', 'suruh', 'kasih', 'minta', 'tanya', 'palak',
            'tertib', 'ide', 'hitung', 'peras', 'kampung',
            'masuk', 'retribusi', 'jamin', 'lebih', 'transparan', 'tolak',
            
            // Important words
            'tidak', 'bukan', 'jangan', 'belum', 'akan', 'sudah', 'masih', 'lagi',
            'rakyat', 'warga', 'masyarakat', 'negara', 'daerah', 'pusat', 'pasti',
            'sampai', 'sendiri', 'berarti', 'terus', 'banget', 'benar',
            'tahun', 'setahun', 'pekerjaan', 'keberatan', 'seharusnya', 'kemudian',
            'wajib', 'harus', 'boleh', 'bisa', 'dapat', 'mampu', 'ingin', 'semoga',
            'jalan', 'rumah', 'tanah', 'lahan', 'garasi', 'halaman', 'tempat',
            'uang', 'duit', 'biaya', 'bayar', 'gaji', 'utang', 'denda', 'setoran',
            'keluar', 'masuk', 'bawa', 'ambil', 'tarik', 'narik',
            'siksa', 'bacok', 'hukum', 'bunuh', 'mati', 'susah', 'mampus', 'sadar',
            'ngentot', 'kontol', 'memek', 'bangsat', 'brengsek', 'bajingan',
            'anjing', 'asu', 'tai', 'taek', 'jingan', 'bacok', 'preman',
            'lapor', 'polis', 'polisi', 'hakim', 'jaksa', 'pengadilan',
            'denda', 'pidana', 'penjara', 'tahan', 'sita', 'tilang',
            'opsen', 'pkb', 'swdkllj', 'ppn', 'pph', 'pbb', 'sajikan',
        ];
    }

    /**
     * Check if a word is in the protected list
     *
     * @param string $word
     * @return bool
     */
    public function isProtectedWord($word)
    {
        return in_array($word, $this->getProtectedWords());
    }

    /**
     * Check if a word is a negation word (e.g., tidak, belum, jangan)
     *
     * @param string $word
     * @return bool
     */
    public function isNegationWord($word)
    {
        $negations = ['tidak', 'bukan', 'jangan', 'belum', 'tak', 'takkan', 
                      'takpernah', 'ga', 'gak', 'nggak', 'enggak', 'tidak usah', 
                      'tidak mau', 'pasti'];
        return in_array($word, $negations);
    }

    /**
     * Iterate through tokens and apply stemming rules.
     * Protects specific words, negation words, and removes duplicates.
     *
     * @param array $tokens
     * @return array
     */
    public function handleStemming($tokens)
    {
        $finalTokens = [];
        $tokens = array_filter($tokens);
        
        foreach ($tokens as $token) {
            if (strlen($token) <= 2 && !$this->isNegationWord($token)) {
                continue;
            }
            
            if ($this->isProtectedWord($token)) {
                $finalTokens[] = $token;
                continue;
            }
            
            $stemmed = $this->applyStemmingRules($token);
            
            if ($this->isProtectedWord($stemmed)) {
                $finalTokens[] = $stemmed;
                continue;
            }
            
            $finalResult = $this->finalClean($stemmed);
            if ($finalResult !== '' && $finalResult !== null) {
                $finalTokens[] = $finalResult;
            }
        }
        
        $seen = [];
        $result = [];
        foreach ($finalTokens as $token) {
            if (!isset($seen[$token])) {
                $seen[$token] = true;
                $result[] = $token;
            }
        }
        
        return $result;
    }
    
    /**
     * Apply Indonesian stemming rules (removing prefixes and suffixes)
     * while preserving specific core words.
     *
     * @param string $word
     * @return string
     */
    public function applyStemmingRules($word)
    {
        if (strlen($word) <= 2) {
            return $word;
        }
        
        $preserveWords = [
            'mending', 'banget', 'benar', 'salah', 'rugi', 'untung', 'demo', 
            'protes', 'dukung', 'setuju', 'tolak', 'ogah', 'mau', 'hilang', 
            'ilang', 'aman', 'bisa', 'dapat', 'harus', 'wajib', 'mampu', 
            'ingin', 'semoga', 'capek', 'males', 'seneng', 'benci', 'suka', 
            'resah', 'peduli', 'percaya', 'yakin', 'jamin', 'janji', 'sumpah',
            'kerja', 'urus', 'bayar', 'jalan', 'rumah', 'tanah', 'lahan',
            'sampai', 'sendiri', 'berarti', 'terus', 'setelah', 'seharusnya',
            'kemudian', 'keberatan', 'pekerjaan', 'tahun', 'setahun',
            'rapih', 'getol', 'bener', 'kacau', 'aneh', 'ngawur', 'nyeleneh',
            'bagus', 'keren', 'mantap', 'oke', 'sepakat', 'asuransi', 'menguntungkan',
            'gila', 'tolol', 'goblok', 'bodoh', 'bego', 'bacok', 'hukum', 'siksa',
            'solusi', 'brilian', 'cerdas', 'pinter', 'bijak', 'tegas', 'kasih',
            'kang', 'bang', 'pak', 'bu', 'mas', 'mbak', 'dek', 'kak', 'lur',
            'gacoan', 'indomaret', 'alfamart', 'samsat', 'stnk', 'pasti', 'koordinir',
            'minta', 'tanya', 'palak', 'sajikan',
            
            // Keep important words
            'berantas', 'basmi', 'cari', 'rampok', 'suruh', 'dishub',
            'kampung', 'tertib', 'ide', 'hitung', 'peras',
            'masuk', 'retribusi', 'jamin', 'lebih', 'transparan', 'tolak',
        ];
        
        if (in_array($word, $preserveWords)) {
            return $word;
        }
        
        $fixedOverrides = $this->getFixedOverrides();
        if (isset($fixedOverrides[$word])) {
            return $fixedOverrides[$word];
        }
        
        $word = preg_replace('/(ku|mu|nya)$/', '', $word);
        if (strlen($word) <= 2) return '';
        
        $word = $this->removePrefixes($word);
        $word = $this->removeSuffixes($word);
        $word = preg_replace('/[^a-z]/', '', $word);
        
        if (strlen($word) <= 2) {
            return '';
        }
        
        return $word;
    }
    
    /**
     * Retrieve a map of fixed overrides for words that fail standard stemming
     *
     * @return array
     */
    public function getFixedOverrides()
    {
        return [
            'hukumnyabacok' => 'hukum bacok',
            'parkirbayar' => 'parkir bayar',
            'bayarpajak' => 'bayar pajak',
            'parkirgratis' => 'parkir gratis',
            'parkirliar' => 'parkir liar',
            'semuanya' => 'semua',
            'peraturan' => 'aturan',
            'pemerintahan' => 'pemerintah',
            'kebijakan' => 'kebijakan',
            'perkotaan' => 'kota',
            'pedesaan' => 'desa',
            'pembayaran' => 'bayar',
            'pendapatan' => 'dapat',
            'pengeluaran' => 'keluar',
            'perparkiran' => 'parkir',
            'ngeluarin' => 'keluar',
            'ngeluh' => 'keluh',
            'ngerjain' => 'kerja',
            'ngerampok' => 'rampok',
            'nambahin' => 'tambah',
            'benerin' => 'benar',
            'urusan' => 'urus',
            'tukangparkir' => 'tukang parkir',
            'berlangganan' => 'langganan',
            'gacoan' => 'gacoan',
            'sendiripun' => 'sendiri',
            'berartipun' => 'berarti',
            'ngasih' => 'kasih',
            'aduuuh' => 'aduh',
            'yael' => 'yael',
            'adaaa' => 'ada',
            'diparkiri' => 'parkir',
            'dipalakin' => 'palak',
            'dimintain' => 'minta',
            'disajikan' => 'sajikan',
            'ditanyain' => 'tanya',
            'memberantas' => 'berantas',
            'diberantas' => 'berantas',
            'pemberantasan' => 'berantas',
            'membasmi' => 'basmi',
            'dibasmi' => 'basmi',
            'pembasmian' => 'basmi',
            'nyari' => 'cari',
            'perampokan' => 'rampok',
            'disuruh' => 'suruh',
            'dishuub' => 'dishub',
            'dikampung' => 'kampung',
            'brantas' => 'berantas',
            'nertibin' => 'tertib',
            'ngide' => 'ide',
            'itungannya' => 'hitung',
            'itungan' => 'hitung',
            'diperas' => 'peras',
            
            // ======================
            // NEW FIXES
            // ======================
            'termasuk' => 'masuk',
            'mnjamin' => 'jamin',
            'bsa' => '',
            'lbh' => 'lebih',
            'tranparan' => 'transparan',
            't o l a k' => 'tolak',
        ];
    }
    
    /**
     * Remove standard Indonesian prefixes (me-, di-, pe-, ter-, dll.)
     *
     * @param string $word
     * @return string
     */
    public function removePrefixes($word)
    {
        $prefixes = [
            'memper', 'mempel', 'menge', 'meng', 'meny', 'memp', 'mem', 'men', 'me',
            'diper', 'dipel', 'dike', 'dik', 'dis', 'dip', 'di',
            'terper', 'terpe', 'terme', 'term', 'ter', 'ber', 'per', 'pe', 'ke', 'se'
        ];
        
        foreach ($prefixes as $prefix) {
            $prefixLength = strlen($prefix);
            
            if (strpos($word, $prefix) === 0 && strlen($word) > $prefixLength + 3) {
                $stem = substr($word, $prefixLength);
                
                if ($prefix === 'meny') {
                    $stem = 's' . $stem;
                } elseif ($prefix === 'meng' && strpos($stem, 'g') === 0) {
                    $stem = substr($stem, 1);
                } elseif (in_array($prefix, ['mem', 'memp']) && strpos($stem, 'p') === 0) {
                    $stem = substr($stem, 1);
                } elseif ($prefix === 'men' && strpos($stem, 'p') === 0) {
                    $stem = substr($stem, 1);
                } elseif ($prefix === 'ber' && strpos($stem, 'r') === 0) {
                    $stem = substr($stem, 1);
                } elseif ($prefix === 'per' && strpos($stem, 'r') === 0) {
                    $stem = substr($stem, 1);
                } elseif ($prefix === 'se') {
                    return $word;
                }
                
                if (strlen($stem) >= 3) {
                    return $stem;
                }
                break;
            }
        }
        
        return $word;
    }
    
    /**
     * Remove standard Indonesian suffixes (-kan, -an, -i)
     *
     * @param string $word
     * @return string
     */
    public function removeSuffixes($word)
    {
        $suffixes = ['kan', 'an', 'i'];
        
        foreach ($suffixes as $suffix) {
            if (substr($word, -strlen($suffix)) === $suffix && strlen($word) > strlen($suffix) + 3) {
                $stem = substr($word, 0, -strlen($suffix));
                if (strlen($stem) >= 3) {
                    return $stem;
                }
                break;
            }
        }
        
        return $word;
    }
    
    /**
     * Apply final cleaning and spelling corrections after stemming has been performed.
     *
     * @param string $word
     * @return string|null
     */
    public function finalClean($word)
    {
        if (strlen($word) <= 2) {
            if ($this->isNegationWord($word)) {
                return $word;
            }
            return '';
        }
        
        $corrections = [
            'maa' => 'maaf', 'bangetkap' => 'banget', 'bangett' => 'banget',
            'anj' => 'anjing', 'gamau' => 'tidak mau', 'gakmau' => 'tidak mau',
            'jgn' => 'jangan', 'dmn' => 'dimana', 'gmn' => 'bagaimana',
            'ntar' => 'nanti', 'lgsg' => 'langsung', 'krn' => 'karena',
            'jd' => 'jadi', 'sm' => 'sama', 'dgn' => 'dengan', 'utk' => 'untuk',
            'tdk' => 'tidak', 'spt' => 'seperti', 'tp' => 'tetapi',
            'klo' => 'kalau', 'blm' => 'belum',
            'byr' => 'bayar', 'dpt' => 'dapat', 'bs' => 'bisa',
            'msh' => 'masih', 'slalu' => 'selalu', 'brrti' => 'berarti',
            'stiap' => 'setiap', 'bgt' => 'banget', 'ayoo' => 'ayo',
            'gpp' => 'tidak apa apa',
            'mainnya' => 'main', 'tross' => 'terus', 'tros' => 'terus',
            'rusa' => 'rusak', 'berla' => 'berlaku', 'belaku' => 'berlaku',
            
            // Verb corrections
            'alakin' => 'palak',
            'palakin' => 'palak',
            'mintain' => 'minta',
            'nga' => 'kasih',
            'dika' => 'kasih',
            'dita' => 'tanya',
            'ajik' => 'sajikan',
            'ngasih' => 'kasih',
            'ngasi' => 'kasih',
            
            // Previous fixes
            'nyar' => 'cari',
            'ampo' => 'rampok',
            'uruh' => 'suruh',
            'huub' => 'dishub',
            'ampung' => 'kampung',
            'brantas' => 'berantas',
            'nertibin' => 'tertib',
            'ngide' => 'ide',
            'itungannya' => 'hitung',
            'diperas' => 'peras',
            
            // ======================
            // NEW FIXES
            // ======================
            'mnjamin' => 'jamin',
            'lbh' => 'lebih',
            'tranparan' => 'transparan',
            
            'ajamending' => 'mending', 'ajamendingan' => 'mending', 'mendingaja' => 'mending',
            'gass' => '', 'gasss' => '', 'gassco' => '',
            'solus' => 'solusi', 'brili' => 'brilian', 'brilian' => 'brilian',
            'cerdas' => 'cerdas', 'pinter' => 'pinter', 'bijak' => 'bijak', 'tegas' => 'tegas',
            'taun' => 'tahun', 'setaun' => 'setahun', 'th' => 'tahun',
            'sampa' => 'sampai', 'sampe' => 'sampai',
            'sendir' => 'sendiri', 'berart' => 'berarti',
            'sete' => 'setelah', 'seharus' => 'seharusnya',
            'mudi' => 'kemudian', 'beratan' => 'keberatan',
            'napa' => 'kenapa', 'rjaan' => 'pekerjaan', 'kerjaan' => 'pekerjaan',
            'ngerjain' => 'kerja',
            'merin' => 'pemerintah', 'merintah' => 'pemerintah', 'mren' => 'pemerintah',
            'merenta' => 'pemerintah', 'merentaa' => 'pemerintah', 
            'shub' => 'dishub', 'mda' => 'pemda', 'mkot' => 'pemkot',
            'dpr' => 'dpr', 'negr' => 'negara', 'polri' => 'polri', 'tni' => 'tni',
            'apbn' => 'apbn', 'apbd' => 'apbd', 'stnk' => 'stnk', 'samsat' => 'samsat',
            'war' => 'warga', 'warg' => 'warga',
            'rakyatn' => 'rakyat', 'publikn' => 'publik', 'hasiln' => 'hasil',
            'orangn' => 'orang', 'motoran' => 'motor', 'mobiln' => 'motor',
            'rumahn' => 'rumah', 'jalanan' => 'jalan', 'lapangan' => 'lapangan',
            'lapangn' => 'lapangan', 'masukin' => 'masuk', 'keluarin' => 'keluar',
            'nagih' => 'tagih', 'ngejar' => 'kejar',
            'adaaa' => '', 'adaa' => '', 'aduuuh' => '', 'yael' => '', 'yaelah' => '',
            'wkwkwkwk' => '', 'wkwkwk' => '', 'wkwk' => '', 'wkwwk' => '', 'wkwkk' => '',
            'wkwkwkwkwk' => '', 'hahaha' => '', 'hehehe' => '', 'hihi' => '',
            'hadeh' => '', 'hadeuh' => '', 'halah' => '', 'waduh' => '',
            'gilee' => '', 'anjay' => '', 'njir' => '', 'njirr' => '',
            'ciee' => '', 'cie' => '', 'waw' => '', 'astaga' => '',
            'hehehhe' => '', 'hahahaha' => '', 'wkwkwkwwk' => '',
            '' => null,
        ];
        
        if (preg_match('/^[wkh]+$/i', $word) && strlen($word) <= 6) {
            return '';
        }
        
        if (isset($corrections[$word])) {
            return $corrections[$word];
        }
        
        return $word;
    }

}
