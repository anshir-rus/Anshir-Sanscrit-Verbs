function sanskrittransliterate(type, direction, input, strict) {
  if (input && (direction === "latin2devanagari" || direction === "latin2ISO")) {
    var latinToDevanagari;
    var diacritics;
    var anuswaraEndings;
    var letterAfterAnuswara;
    var longVyanjana;
    var anunasika;

    let resultSa = "";

    // Input ( ISO-15919 | IAST | SLP | HK ) converted live to ISO-15919 and transliterate to Devanagari
      
    if (type === "SLP") {
      
      latinToDevanagari = { "0": "०", "1": "१", "2": "२", "3": "३", "4": "४", "5": "५", "6": "६", "7": "७", "8": "८", "9": "९", " ": "  ", ".": ".", ",": ",", ";": ";", "?": "?", "!": "!", "\"": "\"", "'": "'", "(": "(", ")": ")", ":": ":", "+": "+", "=": "=", "/": "/", "-": "-", "<": "<", ">": ">", "*": "*", "|": "|", "\\": "\\", "₹": "₹", "{": "{", "}": "}", "[": "[", "]": "]", "_": "_", "%": "%", "@": "@", "ˆ": "ˆ", "`": "`", "´": "´", "·": "·", "˙": "˙", "¯": "¯", "¨": "¨", "˚": "˚", "˝": "˝", "ˇ": "ˇ", "¸": "¸", "˛": "˛", "˘": "˘", "’": "’", "a": "अ", "ā": "आ", "ê": "ॲ", "ô": "ऑ", "i": "इ", "ī": "ई", "u": "उ", "ū": "ऊ", "r̥": "ऋ", "r̥̄": "ॠ", "l̥": "ऌ", "l̥̄": "ॡ", "ê": "ऍ", "e": "ऎ", "ē": "ए", "ai": "ऐ", "o": "ऒ", "ō": "ओ", "au": "औ", "aṁ": "अं", "aḥ": "अः", "ka": "क", "kha": "ख", "ga": "ग", "gha": "ध", "ṅa": "ङ", "ca": "च", "cha": "छ", "ja": "ज", "jha": "झ", "ña": "ञ", "ṭa": "ट", "ṭha": "ठ", "ḍa": "ड", "ḍha": "ढ", "ṇa": "ण", "ta": "त", "tha": "थ", "da": "द", "dha": "ध", "na": "न", "pa": "प", "pha": "फ", "ba": "ब", "bha": "भ", "ma": "म", "ya": "य", "ra": "र", "la": "ल", "va": "व", "śa": "श", "ṣa": "ष", "sa": "स", "ha": "ह", "ḷa": "ळ", "ōm̐":"ॐ", ".":"॰", "̍":"\u0951", "\u0301":"\u0951", "̱":"\u0952", "\u0332":"\u0952", "\u1CF5":"\u1CF5", "\u1CF6":"\u1CF6", "\uA8EB":"\uA8EB" };

      diacritics = { "ā": "ा", "ê": "ॅ", "ô": "ॉ", "i": "ि", "ī": "ी", "u": "ु", "ū": "ू", "r̥": "ृ", "r̥̄": "ॄ", "l̥": "ॢ", "l̥̄": "ॣ", "e": "ॆ", "ē": "े", "ai": "ै", "o": "ॊ", "ō": "ो", "au": "ौ", "aṇ": "ं", "aṁ": "ं", "aḥ": "ः", "ʾ": "़", "m̐": "ँ", "'": "ऽ", "’":"ऽ", "˜": "ँ", "ã": "ँ", "ā̃": "ाँ", "ĩ": "िँ", "ī̃": "ीँ", "ũ": "ुँ", "ū̃": "ूँ", "r̥̃": "ृँ", "ṝ̃": "ॄँ", "ẽ": "ॆँ", "ē̃": "ेँ", "õ": "ॊँ", "ō̃": "ोँ", "āṁ":"ां", "iṁ":"िं", "īṁ":"ीं", "uṁ":"ुं", "ūṁ":"ूं", "r̥ṁ":"ृं", "r̥̄ṁ":"ॄं", "l̥ṁ":"ॢं", "l̥̄ṁ":"ॣं", "eṁ":"ॆं", "ēṁ":"ें", "aiṁ":"ैं", "oṁ":"ॊं", "ōṁ":"ों", "auṁ":"ौं", "Āṁ":"ां", "Iṁ":"िं", "Īṁ":"ीं", "Uṁ":"ुं", "Ūṁ":"ूं", "R̥ṁ":"ृं", "Ṝṁ":"ॄं", "L̥ṁ":"ॢं", "L̥̄ṁ":"ॣं", "Eṁ":"ॆं", "Ēṁ":"ें", "Aiṁ":"ैं", "Oṁ":"ॊं", "Ōṁ":"ों", "Auṁ":"ौं" };
      
      if (!strict) {
        anuswaraEndings = ['ṁ'];
      } else {
        anuswaraEndings = ['ṁ', 'ṇ', 'ṅ', 'ñ', 'n', 'm'];
      }
      letterAfterAnuswara = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd', 'p', 'b', 'y', 'r', 'v', 'ś', 'ṣ', 's', 'h'];
      longVyanjana = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd'];

      anunasika = { "ã": "a", "ā̃": "ā", "ĩ": "i", "ī̃": "ī", "ũ": "u", "ū̃": "ū", "r̥̃": "r̥", "ṝ̃": "r̥̄", "ẽ": "e", "ē̃": "ē", "õ": "o", "ō̃": "ō" };

      /* Sanskrit Library Phonetics : https://en.wikipedia.org/wiki/SLP1
      const SLP2ISO = {"a": "a", "A": "ā", "i": "i", "I": "ī", "u": "u", "U": "ū", "f": "r̥", "F": "r̥̄", "x": "l̥", "X": "l̥̄", "e": "ē", "E": "ai", "o": "ō", "O": "au", "M": "aṁ", "H": "aḥ", "~": "m̐", "k": "k", "K": "kh", "g": "g", "G": "gh", "N": "ṅ", "c": "c", "C": "ch", "j": "j", "J": "jh", "Y": "ñ", "w": "ṭ", "W": "ṭh", "q": "ḍ", "Q": "ḍh", "R": "ṇ", "t": "t", "T": "th", "d": "d", "D": "dh", "n": "n", "p": "p", "P": "ph", "b": "b", "B": "bh", "m": "m", "y": "y", "r": "r", "l": "l", "v": "v", "S": "ś", "z": "ṣ", "s": "s", "h": "h", "L": "ḷ", "'": "'", "/": "\u0952", "\\": "\u0951", "^": "\u1ce0", "Z": "\u1CF5", "V": "\u1CF6"};
      https://www.sanskrit-lexicon.uni-koeln.de/scans/MWScan/2020/web/webtc1/help/accents.html
        Udatta, anudatta and svarita are encoded as "/", "\" and "^" respectively
        Jihvamuliya and upadhmaniya are encoded as "Z" and "V" respectively 
      */
        
      input = input.replaceAll("A","ā").replaceAll("I","ī").replaceAll("U","ū").replaceAll("f","r̥").replaceAll("F","r̥̄").replaceAll("x","l̥").replaceAll("X","l̥̄").replaceAll("e","ē").replaceAll("E","ai").replaceAll("o","ō").replaceAll("O","au").replaceAll("M","ṁ").replaceAll("H","ḥ").replaceAll("~","m̐").replaceAll("K","kh").replaceAll("G","gh").replaceAll("N","ṅ").replaceAll("C","ch").replaceAll("J","jh").replaceAll("Y","ñ").replaceAll("w","ṭ").replaceAll("W","ṭh").replaceAll("q","ḍ").replaceAll("Q","ḍh").replaceAll("R","ṇ").replaceAll("T","th").replaceAll("D","dh").replaceAll("P","ph").replaceAll("B","bh").replaceAll("S","ś").replaceAll("z","ṣ").replaceAll("L","ḷ").replaceAll("^","̍").replaceAll("ˆ","̍").replaceAll("â","a̍").replaceAll("ê","e̍").replaceAll("î","i̍").replaceAll("ô","o̍").replaceAll("û","u̍").replaceAll("ā̂","ā̍").replaceAll("ē̂","ē̍").replaceAll("ī̂","ī̍").replaceAll("ō̂","ō̍").replaceAll("ū̂","ū̍").replaceAll("\\","̱").replaceAll("/","\uA8EB").replaceAll("Z","\u1CF5").replaceAll("V","\u1CF6"); 
      // When required : Ā̀ Â Ā̂ Ā́ Ḕ Ê Ē̂ Ḗ Ī̀ Î Ī̂ Ī́ Ṑ Ô Ō̂ Ṓ Ū̀ Û Ū̂ Ū́ : all combinations for Vedic accents

      if (direction === "latin2ISO") {
        return input;
      }

    } else if (type === "HK") {
      
      latinToDevanagari = { "0": "०", "1": "१", "2": "२", "3": "३", "4": "४", "5": "५", "6": "६", "7": "७", "8": "८", "9": "९", " ": "  ", ".": ".", ",": ",", ";": ";", "?": "?", "!": "!", "\"": "\"", "'": "'", "(": "(", ")": ")", ":": ":", "+": "+", "=": "=", "/": "/", "-": "-", "<": "<", ">": ">", "*": "*", "|": "|", "\\": "\\", "₹": "₹", "{": "{", "}": "}", "[": "[", "]": "]", "_": "_", "%": "%", "@": "@", "ˆ": "ˆ", "`": "`", "´": "´", "·": "·", "˙": "˙", "¯": "¯", "¨": "¨", "˚": "˚", "˝": "˝", "ˇ": "ˇ", "¸": "¸", "˛": "˛", "˘": "˘", "’": "’", "a": "अ", "ā": "आ", "ê": "ॲ", "ô": "ऑ", "i": "इ", "ī": "ई", "u": "उ", "ū": "ऊ", "r̥": "ऋ", "r̥̄": "ॠ", "l̥": "ऌ", "l̥̄": "ॡ", "ê": "ऍ", "e": "ऎ", "ē": "ए", "ai": "ऐ", "o": "ऒ", "ō": "ओ", "au": "औ", "aṁ": "अं", "aḥ": "अः", "ka": "क", "kha": "ख", "ga": "ग", "gha": "ध", "ṅa": "ङ", "ca": "च", "cha": "छ", "ja": "ज", "jha": "झ", "ña": "ञ", "ṭa": "ट", "ṭha": "ठ", "ḍa": "ड", "ḍha": "ढ", "ṇa": "ण", "ta": "त", "tha": "थ", "da": "द", "dha": "ध", "na": "न", "pa": "प", "pha": "फ", "ba": "ब", "bha": "भ", "ma": "म", "ya": "य", "ra": "र", "la": "ल", "va": "व", "śa": "श", "ṣa": "ष", "sa": "स", "ha": "ह", "ḷa": "ळ", "ōm̐":"ॐ", ".":"॰" };

      diacritics = { "ā": "ा", "ê": "ॅ", "ô": "ॉ", "i": "ि", "ī": "ी", "u": "ु", "ū": "ू", "r̥": "ृ", "r̥̄": "ॄ", "l̥": "ॢ", "l̥̄": "ॣ", "e": "ॆ", "ē": "े", "ai": "ै", "o": "ॊ", "ō": "ो", "au": "ौ", "aṇ": "ं", "aṁ": "ं", "aḥ": "ः", "ʾ": "़", "m̐": "ँ", "'": "ऽ", "’":"ऽ", "˜": "ँ", "ã": "ँ", "ā̃": "ाँ", "ĩ": "िँ", "ī̃": "ीँ", "ũ": "ुँ", "ū̃": "ूँ", "r̥̃": "ृँ", "ṝ̃": "ॄँ", "ẽ": "ॆँ", "ē̃": "ेँ", "õ": "ॊँ", "ō̃": "ोँ", "āṁ":"ां", "iṁ":"िं", "īṁ":"ीं", "uṁ":"ुं", "ūṁ":"ूं", "r̥ṁ":"ृं", "r̥̄ṁ":"ॄं", "l̥ṁ":"ॢं", "l̥̄ṁ":"ॣं", "eṁ":"ॆं", "ēṁ":"ें", "aiṁ":"ैं", "oṁ":"ॊं", "ōṁ":"ों", "auṁ":"ौं", "Āṁ":"ां", "Iṁ":"िं", "Īṁ":"ीं", "Uṁ":"ुं", "Ūṁ":"ूं", "R̥ṁ":"ृं", "Ṝṁ":"ॄं", "L̥ṁ":"ॢं", "L̥̄ṁ":"ॣं", "Eṁ":"ॆं", "Ēṁ":"ें", "Aiṁ":"ैं", "Oṁ":"ॊं", "Ōṁ":"ों", "Auṁ":"ौं" };

      if (!strict) {
        anuswaraEndings = ['ṁ'];
      } else {
        anuswaraEndings = ['ṁ', 'ṇ', 'ṅ', 'ñ', 'n', 'm'];
      }
      letterAfterAnuswara = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd', 'p', 'b', 'y', 'r', 'v', 'ś', 'ṣ', 's', 'h'];
      longVyanjana = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd'];

      anunasika = { "ã": "a", "ā̃": "ā", "ĩ": "i", "ī̃": "ī", "ũ": "u", "ū̃": "ū", "r̥̃": "r̥", "ṝ̃": "r̥̄", "ẽ": "e", "ē̃": "ē", "õ": "o", "ō̃": "ō" };

      /* Harvard-Kyoto System : https://en.wikipedia.org/wiki/Harvard-Kyoto
      const HK2ISO = {"a": "a", "A": "ā", "i": "i", "I": "ī", "u": "u", "U": "ū", "R": "r̥", "RR": "r̥̄", "lR": "l̥", "lRR": "l̥̄", "e": "ē", "AI": "ai", "o": "ō", "au": "au", "aM": "aṁ", "aH": "aḥ", "k": "k", "kh": "kh", "g": "g", "gh": "gh", "G": "ṅ", "c": "c", "ch": "ch", "j": "j", "jh": "jh", "J": "ñ", "T": "ṭ", "Th": "ṭh", "D": "ḍ", "Dh": "ḍh", "N": "ṇ", "t": "t", "th": "th", "d": "d", "dh": "dh", "n": "n", "p": "p", "ph": "ph", "b": "b", "bh": "bh", "m": "m", "y": "y", "r": "r", "l": "l", "v": "v", "z": "ś", "S": "ṣ", "s": "s", "h": "h", "L": "ḷ"}; */
      input = input.replaceAll("AI","ai").replaceAll("aM","aṁ").replaceAll("aH","aḥ").replaceAll("A","ā").replaceAll("I","ī").replaceAll("U","ū").replaceAll("lRR","l̥̄").replaceAll("RR","r̥̄").replaceAll("lR","l̥").replaceAll("R","r̥").replaceAll("e","ē").replaceAll("o","ō").replaceAll("~","m̐").replaceAll("G","ṅ").replaceAll("J","ñ").replaceAll("Th","ṭh").replaceAll("T","ṭ").replaceAll("Dh","ḍh").replaceAll("D","ḍ").replaceAll("N","ṇ").replaceAll("z","ś").replaceAll("S","ṣ").replaceAll("L","ḷ");

      if (direction === "latin2ISO") {
        return input;
      }

    } else if (type === "IAST") {
      
      latinToDevanagari = { "0": "०", "1": "१", "2": "२", "3": "३", "4": "४", "5": "५", "6": "६", "7": "७", "8": "८", "9": "९", " ": "  ", ".": ".", ",": ",", ";": ";", "?": "?", "!": "!", "\"": "\"", "'": "'", "(": "(", ")": ")", ":": ":", "+": "+", "=": "=", "/": "/", "-": "-", "<": "<", ">": ">", "*": "*", "|": "|", "\\": "\\", "₹": "₹", "{": "{", "}": "}", "[": "[", "]": "]", "_": "_", "%": "%", "@": "@", "ˆ": "ˆ", "`": "`", "´": "´", "·": "·", "˙": "˙", "¯": "¯", "¨": "¨", "˚": "˚", "˝": "˝", "ˇ": "ˇ", "¸": "¸", "˛": "˛", "˘": "˘", "’": "’", "a": "अ", "ā": "आ", "ê": "ॲ", "ô": "ऑ", "i": "इ", "ī": "ई", "u": "उ", "ū": "ऊ", "r̥": "ऋ", "r̥̄": "ॠ", "l̥": "ऌ", "l̥̄": "ॡ", "ê": "ऍ", "e": "ऎ", "ē": "ए", "ai": "ऐ", "o": "ऒ", "ō": "ओ", "au": "औ", "aṁ": "अं", "aḥ": "अः", "ka": "क", "kha": "ख", "ga": "ग", "gha": "ध", "ṅa": "ङ", "ca": "च", "cha": "छ", "ja": "ज", "jha": "झ", "ña": "ञ", "ṭa": "ट", "ṭha": "ठ", "ḍa": "ड", "ḍha": "ढ", "ṇa": "ण", "ta": "त", "tha": "थ", "da": "द", "dha": "ध", "na": "न", "pa": "प", "pha": "फ", "ba": "ब", "bha": "भ", "ma": "म", "ya": "य", "ra": "र", "la": "ल", "va": "व", "śa": "श", "ṣa": "ष", "sa": "स", "ha": "ह", "ḷa": "ळ", "qa": "क़", "k͟ha": "ख़", "ġa": "ग़", "za": "ज़", "ža": "झ़", "ṛa":"ड़", "ṛha": "ढ़", "t̤a": "त़", "s̱a": "थ़", "fa": "फ़", "wa": "व़", "s̤a": "स़", "h̤a": "ह़", "ōm̐":"ॐ", "Ōm̐":"ॐ", ".":"॰", "A": "अ", "Ā": "आ", "Ê": "ॲ", "Ô": "ऑ", "I": "इ", "Ī": "ई", "U": "उ", "Ū": "ऊ", "R̥": "ऋ", "Ṝ": "ॠ", "L̥": "ऌ", "L̥̄": "ॡ", "Ê": "ऍ", "E": "ऎ", "Ē": "ए", "Ai": "ऐ", "O": "ऒ", "Ō": "ओ", "Au": "औ", "Aṁ": "अं", "Aḥ": "अः", "Ka": "क", "Kha": "ख", "Ga": "ग", "Gha": "घ", "Ṅa": "ङ", "Ca": "च", "Cha": "छ", "Ja": "ज", "Jha": "झ", "Ña": "ञ", "Ṭa": "ट", "Ṭha": "ठ", "Ḍa": "ड", "Ḍha": "ढ", "Ṇa": "ण", "Ta": "त", "Tha": "थ", "Da": "द", "Dha": "ध", "Na": "न", "Pa": "प", "Pha": "फ", "Ba": "ब", "Bha": "भ", "Ma": "म", "Ya": "य", "Ra": "र", "La": "ल", "Va": "व", "Śa": "श", "Ṣa": "ष", "Sa": "स", "Ha": "ह", "Ḷa": "ळ", "Qa": "क़", "Ḵha": "ख़", "Ġa": "ग़", "Za": "ज़",  "Ža": "झ़", "Ṛa":"ड़", "Ṛha": "ढ़", "T̤a": "त़", "S̱a": "थ़", "fa": "फ़", "Wa": "व़", "S̤a": "स़", "H̤a": "ह़" };

      diacritics = { "ā": "ा", "ê": "ॅ", "ô": "ॉ", "i": "ि", "ī": "ी", "u": "ु", "ū": "ू", "r̥": "ृ", "r̥̄": "ॄ", "l̥": "ॢ", "l̥̄": "ॣ", "e": "ॆ", "ē": "े", "ai": "ै", "o": "ॊ", "ō": "ो", "au": "ौ", "aṇ": "ं", "aṁ": "ं", "aḥ": "ः", "ʾ": "़", "m̐": "ँ", "'": "ऽ", "’":"ऽ", "˜": "ँ", "ã": "ँ", "ā̃": "ाँ", "ĩ": "िँ", "ī̃": "ीँ", "ũ": "ुँ", "ū̃": "ूँ", "r̥̃": "ृँ", "ṝ̃": "ॄँ", "ẽ": "ॆँ", "ē̃": "ेँ", "õ": "ॊँ", "ō̃": "ोँ", "Ā": "ा", "Ê": "ॅ", "Ô": "ॉ", "I": "ि", "Ī": "ी", "U": "ु", "Ū": "ू", "R̥": "ृ", "Ṝ": "ॄ", "L̥": "ॢ", "L̥̄": "ॣ", "E": "ॆ", "Ē": "े", "Ai": "ै", "O": "ॊ", "Ō": "ो", "Au": "ौ", "Aṇ": "ं", "Aṁ": "ं", "Aḥ": "ः", "M̐": "ँ", "āṁ":"ां", "iṁ":"िं", "īṁ":"ीं", "uṁ":"ुं", "ūṁ":"ूं", "r̥ṁ":"ृं", "r̥̄ṁ":"ॄं", "l̥ṁ":"ॢं", "l̥̄ṁ":"ॣं", "eṁ":"ॆं", "ēṁ":"ें", "aiṁ":"ैं", "oṁ":"ॊं", "ōṁ":"ों", "auṁ":"ौं", "Āṁ":"ां", "Iṁ":"िं", "Īṁ":"ीं", "Uṁ":"ुं", "Ūṁ":"ूं", "R̥ṁ":"ृं", "Ṝṁ":"ॄं", "L̥ṁ":"ॢं", "L̥̄ṁ":"ॣं", "Eṁ":"ॆं", "Ēṁ":"ें", "Aiṁ":"ैं", "Oṁ":"ॊं", "Ōṁ":"ों", "Auṁ":"ौं" };

      if (!strict) {
        anuswaraEndings = ['ṁ'];
      } else {
        anuswaraEndings = ['ṁ', 'ṇ', 'ṅ', 'ñ', 'n', 'm'];
      }
      letterAfterAnuswara = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd', 'p', 'b', 'y', 'r', 'v', 'ś', 'ṣ', 's', 'h'];
      longVyanjana = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd'];

      anunasika = { "ã": "a", "ā̃": "ā", "ĩ": "i", "ī̃": "ī", "ũ": "u", "ū̃": "ū", "r̥̃": "r̥", "ṝ̃": "r̥̄", "ẽ": "e", "ē̃": "ē", "õ": "o", "ō̃": "ō" };

      // IAST - ISO:15919 (Sanskrit) : https://en.wikipedia.org/wiki/International_Alphabet_of_Sanskrit_Transliteration
      input = input.toLowerCase();
      input = input.replaceAll("ṃ","ṁ").replaceAll("ã","m̐").replaceAll("E","Ē").replaceAll("O","Ō").replaceAll("Ṛ","R̥").replaceAll("Ṝ","R̥̄").replaceAll("Ḷ","L̥").replaceAll("Ḹ","L̥̄").replaceAll("e","ē").replaceAll("o","ō").replaceAll("ṛ","r̥").replaceAll("ṝ","r̥̄").replaceAll("ḷ","l̥").replaceAll("ḹ","l̥̄").replaceAll("Ḻ","Ḷ").replaceAll("ḻ","ḷ");

      if (direction === "latin2ISO") {
        return input;
      }

    } else if (type === "ISO") {
      // Transliteration for Sanskrit (ISO 15919) : https://en.wikipedia.org/wiki/ISO_15919
      
      latinToDevanagari = { "0": "०", "1": "१", "2": "२", "3": "३", "4": "४", "5": "५", "6": "६", "7": "७", "8": "८", "9": "९", " ": "  ", ".": ".", ",": ",", ";": ";", "?": "?", "!": "!", "\"": "\"", "'": "'", "(": "(", ")": ")", ":": ":", "+": "+", "=": "=", "/": "/", "-": "-", "<": "<", ">": ">", "*": "*", "|": "|", "\\": "\\", "₹": "₹", "{": "{", "}": "}", "[": "[", "]": "]", "_": "_", "%": "%", "@": "@", "ˆ": "ˆ", "`": "`", "´": "´", "·": "·", "˙": "˙", "¯": "¯", "¨": "¨", "˚": "˚", "˝": "˝", "ˇ": "ˇ", "¸": "¸", "˛": "˛", "˘": "˘", "’": "’", "a": "अ", "ā": "आ", "ê": "ॲ", "ô": "ऑ", "i": "इ", "ī": "ई", "u": "उ", "ū": "ऊ", "r̥": "ऋ", "r̥̄": "ॠ", "l̥": "ऌ", "l̥̄": "ॡ", "ê": "ऍ", "e": "ऎ", "ē": "ए", "ai": "ऐ", "o": "ऒ", "ō": "ओ", "au": "औ", "aṁ": "अं", "aḥ": "अः", "ka": "क", "kha": "ख", "ga": "ग", "gha": "ध", "ṅa": "ङ", "ca": "च", "cha": "छ", "ja": "ज", "jha": "झ", "ña": "ञ", "ṭa": "ट", "ṭha": "ठ", "ḍa": "ड", "ḍha": "ढ", "ṇa": "ण", "ta": "त", "tha": "थ", "da": "द", "dha": "ध", "na": "न", "pa": "प", "pha": "फ", "ba": "ब", "bha": "भ", "ma": "म", "ya": "य", "ra": "र", "la": "ल", "va": "व", "śa": "श", "ṣa": "ष", "sa": "स", "ha": "ह", "ḷa": "ळ", "qa": "क़", "k͟ha": "ख़", "ġa": "ग़", "za": "ज़", "ža": "झ़", "ṛa":"ड़", "ṛha": "ढ़", "t̤a": "त़", "s̱a": "थ़", "fa": "फ़", "wa": "व़", "s̤a": "स़", "h̤a": "ह़", "ōm̐":"ॐ", "Ōm̐":"ॐ", ".":"॰", "A": "अ", "Ā": "आ", "Ê": "ॲ", "Ô": "ऑ", "I": "इ", "Ī": "ई", "U": "उ", "Ū": "ऊ", "R̥": "ऋ", "Ṝ": "ॠ", "L̥": "ऌ", "L̥̄": "ॡ", "Ê": "ऍ", "E": "ऎ", "Ē": "ए", "Ai": "ऐ", "O": "ऒ", "Ō": "ओ", "Au": "औ", "Aṁ": "अं", "Aḥ": "अः", "Ka": "क", "Kha": "ख", "Ga": "ग", "Gha": "घ", "Ṅa": "ङ", "Ca": "च", "Cha": "छ", "Ja": "ज", "Jha": "झ", "Ña": "ञ", "Ṭa": "ट", "Ṭha": "ठ", "Ḍa": "ड", "Ḍha": "ढ", "Ṇa": "ण", "Ta": "त", "Tha": "थ", "Da": "द", "Dha": "ध", "Na": "न", "Pa": "प", "Pha": "फ", "Ba": "ब", "Bha": "भ", "Ma": "म", "Ya": "य", "Ra": "र", "La": "ल", "Va": "व", "Śa": "श", "Ṣa": "ष", "Sa": "स", "Ha": "ह", "Ḷa": "ळ", "Qa": "क़", "Ḵha": "ख़", "Ġa": "ग़", "Za": "ज़",  "Ža": "झ़", "Ṛa":"ड़", "Ṛha": "ढ़", "T̤a": "त़", "S̱a": "थ़", "fa": "फ़", "Wa": "व़", "S̤a": "स़", "H̤a": "ह़", "̍":"\u0951", "\u0301":"\u0951", "̱":"\u0952", "\u0332":"\u0952", "\u1CF5":"\u1CF5", "\u1CF6":"\u1CF6", "\uA8EB":"\uA8EB" };
      // Yajurveda Independent Svarita "_":"\u1CD7" ?
      // Sāmaveda "1\\":"\uA8E1", "2\\":"\uA8E2", "3\\":"\uA8E1", "u\\":"\uA8EB", "r\\":"\uA8EF"

      diacritics = { "ā": "ा", "ê": "ॅ", "ô": "ॉ", "i": "ि", "ī": "ी", "u": "ु", "ū": "ू", "r̥": "ृ", "r̥̄": "ॄ", "l̥": "ॢ", "l̥̄": "ॣ", "e": "ॆ", "ē": "े", "ai": "ै", "o": "ॊ", "ō": "ो", "au": "ौ", "aṇ": "ं", "aṁ": "ं", "aḥ": "ः", "ʾ": "़", "m̐": "ँ", "'": "ऽ", "’":"ऽ", "˜": "ँ", "ã": "ँ", "ā̃": "ाँ", "ĩ": "िँ", "ī̃": "ीँ", "ũ": "ुँ", "ū̃": "ूँ", "r̥̃": "ृँ", "ṝ̃": "ॄँ", "ẽ": "ॆँ", "ē̃": "ेँ", "õ": "ॊँ", "ō̃": "ोँ", "Ā": "ा", "Ê": "ॅ", "Ô": "ॉ", "I": "ि", "Ī": "ी", "U": "ु", "Ū": "ू", "R̥": "ृ", "Ṝ": "ॄ", "L̥": "ॢ", "L̥̄": "ॣ", "E": "ॆ", "Ē": "े", "Ai": "ै", "O": "ॊ", "Ō": "ो", "Au": "ौ", "Aṇ": "ं", "Aṁ": "ं", "Aḥ": "ः", "M̐": "ँ", "āṁ":"ां", "iṁ":"िं", "īṁ":"ीं", "uṁ":"ुं", "ūṁ":"ूं", "r̥ṁ":"ृं", "r̥̄ṁ":"ॄं", "l̥ṁ":"ॢं", "l̥̄ṁ":"ॣं", "eṁ":"ॆं", "ēṁ":"ें", "aiṁ":"ैं", "oṁ":"ॊं", "ōṁ":"ों", "auṁ":"ौं", "Āṁ":"ां", "Iṁ":"िं", "Īṁ":"ीं", "Uṁ":"ुं", "Ūṁ":"ूं", "R̥ṁ":"ृं", "Ṝṁ":"ॄं", "L̥ṁ":"ॢं", "L̥̄ṁ":"ॣं", "Eṁ":"ॆं", "Ēṁ":"ें", "Aiṁ":"ैं", "Oṁ":"ॊं", "Ōṁ":"ों", "Auṁ":"ौं" };

      if (!strict) {
        anuswaraEndings = ['ṁ'];
      } else {
        anuswaraEndings = ['ṁ', 'ṇ', 'ṅ', 'ñ', 'n', 'm'];
      }
      letterAfterAnuswara = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd', 'p', 'b', 'y', 'r', 'v', 'ś', 'ṣ', 's', 'h'];
      longVyanjana = ['k', 'g', 'c', 'j', 'ṭ', 'ḍ', 't', 'd'];

      anunasika = { "ã": "a", "ā̃": "ā", "ĩ": "i", "ī̃": "ī", "ũ": "u", "ū̃": "ū", "r̥̃": "r̥", "ṝ̃": "r̥̄", "ẽ": "e", "ē̃": "ē", "õ": "o", "ō̃": "ō" };

      input = input.toLowerCase().replaceAll("ˆ","̍").replaceAll("^","̍").replaceAll("á","a\uA8EB").replaceAll("à","a̱").replaceAll("à","a̲").replaceAll("â","a̍").replaceAll("é","e\uA8EB").replaceAll("è","e̱").replaceAll("è","e̲").replaceAll("ê","e̍").replaceAll("í","i\uA8EB").replaceAll("ì","i̱").replaceAll("ì","i̲").replaceAll("î","i̍").replaceAll("ó","o\uA8EB").replaceAll("ò","o̱").replaceAll("ò","o̲").replaceAll("ô","o̍").replaceAll("ú","o\uA8EB").replaceAll("ù","u̱").replaceAll("ù","u̲").replaceAll("û","u̍").replaceAll("ā́","ā\uA8EB").replaceAll("ā̀","ā̱").replaceAll("ā̀","ā̲").replaceAll("ā̂","ā̍").replaceAll("ḗ","ē\uA8EB").replaceAll("ḕ","ē̱").replaceAll("ḕ","ē̲").replaceAll("ē̂","ē̍").replaceAll("ī́","ī\uA8EB").replaceAll("ī̀","ī̱").replaceAll("ī̀","ī̲").replaceAll("ī̂","ī̍").replaceAll("ṓ","ō\uA8EB").replaceAll("ṑ","ō̱").replaceAll("ṑ","ō̲").replaceAll("ō̂","ō̍").replaceAll("ū́","ū\uA8EB").replaceAll("ū̀","ū̱").replaceAll("ū̀","ū̲").replaceAll("ū̂","ū̍"); 
      // When required : Ā̀ Â Ā̂ Ā́ Ḕ Ê Ē̂ Ḗ Ī̀ Î Ī̂ Ī́ Ṑ Ô Ō̂ Ṓ Ū̀ Û Ū̂ Ū́ : all combinations for Vedic accents
    }

    for (let u = 0; u < input.length; u++) {
      if (diacritics[input[u - 2] + input[u - 1] + input[u]]) { // Vowel 3-character
        if (diacritics[input[u - 2] + input[u - 1] + input[u]] && latinToDevanagari[input[u - 2] + input[u - 1] + input[u]] && (input[u - 3] == "" || !input[u - 3] || input[u - 3] == " " || input[u - 3].indexOf("\n") > -1)) { // Standalone 3-character Vowel
          resultSa = resultSa.slice(0, -2) + latinToDevanagari[input[u - 2] + input[u - 1] + input[u]];
        } else {
          resultSa = resultSa.slice(0, -2) + diacritics[input[u - 2] + input[u - 1] + input[u]];
        }
      } else if (!diacritics[input[u - 2]] && diacritics[input[u - 1] + input[u]]) { // Vowel 2-character
        if (diacritics[input[u - 1] + input[u]] && latinToDevanagari[input[u - 1] + input[u]] && (input[u - 2] == "" || !input[u - 2] || input[u - 2] == " " || input[u - 2].indexOf("\n") > -1)) {  // Standalone 2-character Vowel
          if (anuswaraEndings.indexOf(input[u + 1]) > -1 && (letterAfterAnuswara.indexOf(input[u + 2]) > -1 || input[u + 2] == " " || input[u + 2] == "")) {
            resultSa = resultSa.slice(0, -1) + latinToDevanagari[input[u - 1] + input[u]] + "ं"; // Anuswara - V²A  V²AC¹ V²AC²
            u = u + 1;
          } else {
            resultSa = resultSa.slice(0, -1) + latinToDevanagari[input[u - 1] + input[u]];
          }
        } else {
          resultSa = resultSa.slice(0, -1) + diacritics[input[u - 1] + input[u]];
        }
      } else if (!diacritics[input[u - 2]] && !diacritics[input[u - 1]] && diacritics[input[u]]) { // Vowel 1-character
        if (input[u] == "a" && input[u - 1] == " ") {
          resultSa = resultSa.slice(0, -1) + latinToDevanagari[input[u]];
        } else if (diacritics[input[u]] && (input[u - 1] == "" || !input[u - 1] || input[u - 1] == " " || input[u - 1].indexOf("\n") > -1)) { // Standalone 1-character Vowel
          if (anuswaraEndings.indexOf(input[u + 1]) > -1 && (letterAfterAnuswara.indexOf(input[u + 2]) > -1 || input[u + 2] == " " || input[u + 2] == "")) {
            resultSa = resultSa + latinToDevanagari[input[u]] + "ं"; // Anuswara - V¹A  V¹AC¹ V¹AC²
            u = u + 1;
          } else {
            resultSa = resultSa.slice(0, -1) + latinToDevanagari[input[u]];
          }
        } else {
          resultSa = resultSa.slice(0, -1) + diacritics[input[u]];
        }
      } else if (latinToDevanagari[input[u - 1] + input[u] + "a"] || (latinToDevanagari[input[u - 2] + input[u - 1] + "a"] && input[u] == "a")) { // Consonant 2-character
        if (diacritics[input[u] + input[u + 1] + input[u + 2]]) { // Consonant-Vowel 2-character 3-character
          resultSa = resultSa.slice(0, -1) + diacritics[input[u] + input[u + 1] + input[u + 2]];
        } else if (diacritics[input[u] + input[u + 1]]) { // Consonant-Vowel 2-character 2-character
          resultSa = resultSa.slice(0, -1) + diacritics[input[u] + input[u + 1]];
        } else if ((input[u - 1] + input[u]) == "k͟h" || (input[u - 1] + input[u]) == "s̱" || (input[u - 1] + input[u]) == "ṛh" || (input[u - 1] + input[u]) == "s̤" || (input[u - 1] + input[u]) == "h̤" || (input[u - 1] + input[u]) == "t̤" || (input[u - 1] + input[u]) == "ž") { // Nuqta cases
          resultSa = resultSa.slice(0, -3) + latinToDevanagari[input[u - 1] + input[u] + "a"];
        } else if (latinToDevanagari[input[u - 2] + input[u - 1] + "a"] && input[u] == "a") { // Consonant-Vowel 2-character 1-character
          resultSa = resultSa.slice(0, -2) + latinToDevanagari[input[u - 2] + input[u - 1] + "a"];
        } else if (anuswaraEndings.indexOf(input[u + 1]) > -1) {
          resultSa = resultSa + "ं";  // Anuswara - C²A  CD²A  C²AC² C²AC¹ CD²AC² CD²AC¹
        } else {
          resultSa = resultSa.slice(0, -2) + latinToDevanagari[input[u - 1] + input[u] + "a"] + "्";
        }
      } else if (latinToDevanagari[input[u] + "a"] || (latinToDevanagari[input[u - 1] + "a"] && input[u] == "a")) { // Consonant 1-character
        if (diacritics[input[u] + input[u + 1] + input[u + 2]]) { // Consonant-Vowel 1-character 3-character
          resultSa = resultSa.slice(0, -1) + diacritics[input[u] + input[u + 1] + input[u + 2]];
        } else if (diacritics[input[u] + input[u + 1]]) { // Consonant-Vowel 1-character 2-character
          if ((input[u] + input[u + 1]) == "m̐") { // Anunasika
            resultSa = resultSa + diacritics["m̐"];
          } else if (diacritics[input[u - 1]] && input[u] == " ̃") { // vowel nasalisation
            resultSa = resultSa.slice(0, -1) + diacritics[input[u] + input[u + 1]];
          } else {
            resultSa = resultSa.slice(0, -1) + diacritics[input[u] + input[u + 1]];
          }
        } else if (latinToDevanagari[input[u - 1] + "a"] && input[u] == "a") { // Consonant-Vowel 1-character 1-character
          if (input[u - 1] == "q" || input[u - 1] == "ġ" || input[u - 1] == "z" || input[u - 1] == "ṛ" || input[u - 1] == "f" || input[u - 1] == "w") { // Nuqta cases
            resultSa = resultSa.slice(0, -3) + latinToDevanagari[input[u - 1] + "a"];
          } else if (input[u-2] == "a" && input[u-1] == "ṇ" && input[u] == "a") { 
            resultSa = resultSa.slice(0, -1) + latinToDevanagari[input[u-1] + "a"];
          } else {
            resultSa = resultSa.slice(0, -2) + latinToDevanagari[input[u - 1] + "a"];
          }
        } else if ((latinToDevanagari[input[u - 2]] != undefined && diacritics[input[u - 1]] != undefined && anuswaraEndings.indexOf(input[u]) > -1 && letterAfterAnuswara.indexOf(input[u + 1]) > -1 && diacritics[input[u + 2]] != undefined) || ((input[u - 1] == "a" || diacritics[input[u - 1]] != undefined) && anuswaraEndings.indexOf(input[u]) > -1 && letterAfterAnuswara.indexOf(input[u + 1]) > -1)) {
          resultSa = resultSa + "ं"; // Anuswara - C¹A  CD¹A   C¹AC² CD¹AC² C¹AC² CD¹AC² 
        } else {
          resultSa = resultSa + latinToDevanagari[input[u] + "a"] + "्";
        }
      } else if (input[u].indexOf("\n") > -1) { // New Lines
        resultSa = resultSa + "\n";
      } else if (latinToDevanagari[input[u]] != undefined && latinToDevanagari[input[u]] != null && input[u] != "") { // Default Single Character
        if (diacritics[input[u]]) {
          resultSa = resultSa.slice(0, -1) + diacritics[input[u]];
        } else {
          resultSa = resultSa + latinToDevanagari[input[u]];
        }
      }
    }

    if (type === "HK" && (resultSa.indexOf("ऌ") > -1 || resultSa.indexOf("ॡ") > -1 || resultSa.indexOf("ॢ") > -1 || resultSa.indexOf("ॣ") > -1)) { // lR - ऌ or lRR - ॡ to handle lR - लृ or lRR - लॄ resp.
      if (resultSa.indexOf(' ') == -1) { 
        resultSa = resultSa + ' ' + resultSa.replaceAll("ॣ","लॄ").replaceAll("ॢ","लृ").replaceAll("ॡ",'लॄ').replaceAll("ऌ",'लृ');
      } else {
        let unprocessed = resultSa.split(" ");
        let processed = "";

        for (let i = 0; i < unprocessed.length; i++) {
          if (unprocessed[i].indexOf("ऌ") > -1){
            processed = processed + unprocessed[i] + ' ' + unprocessed[i].replaceAll("ऌ","लृ") + ' ';
          } else if (unprocessed[i].indexOf("ॡ") > -1){
            processed = processed + unprocessed[i] + ' ' + unprocessed[i].replaceAll("ॡ","लॄ") + ' ';
          } else if (unprocessed[i].indexOf("ॢ") > -1){
            processed = processed + unprocessed[i] + ' ' + unprocessed[i].replaceAll("ॢ","लृ") + ' ';
          } else if (unprocessed[i].indexOf("ॣ") > -1){
            processed = processed + unprocessed[i] + ' ' + unprocessed[i].replaceAll("ॣ","लॄ") + ' ';
          } else {
            processed = processed + unprocessed[i] + ' ';
          }
        }
        resultSa = processed;
      }
    }

    return resultSa;

  } else if (input && direction === "devanagari2latin") {

    const devanagariToLatin = { "0": "0", "1": "1", "2": "2", "3": "3", "4": "4", "5": "5", "6": "6", "7": "7", "8": "8", "9": "9", "०": "0", "१": "1", "२": "2", "३": "3", "४": "4", "५": "5", "६": "6", "७": "7", "८": "8", "९": "9", " ": " ", "।": ".", "॥": ".", ",": ",", ";": ";", "?": "?", "!": "!", "\"": "\"", "'": "'", "(": "(", ")": ")", ":": ":", "+": "+", "=": "=", "/": "/", "-": "-", "<": "<", ">": ">", "*": "*", "|": "|", "\\": "\\", "₹": "₹", "{": "{", "}": "}", "[": "[", "]": "]", "_": "_", "%": "%", "@": "@", "ˆ": "ˆ", "`": "`", "´": "´", "˜": "˜", "·": "·", "˙": "˙", "¯": "¯", "¨": "¨", "˚": "˚", "˝": "˝", "ˇ": "ˇ", "¸": "¸", "˛": "˛", "˘": "˘", "’": "’", "अ": "a", "आ": "ā", "ॲ": "ê", "ऑ": "ô", "इ": "i", "ई": "ī", "उ": "u", "ऊ": "ū", "ऋ": "r̥", "ॠ": "r̥̄", "ऌ": "l̥", "ॡ": "l̥̄", "ऍ": "ê",  "ऎ": "e", "ए": "ē", "ऐ": "ai", "ऒ": "o", "ओ": "ō", "औ": "au", "अं": "aṁ", "अः": "aḥ", "ँ": "m̐", "क": "ka", "ख": "kha", "ग": "ga", "घ": "gha", "ङ": "ṅa", "च": "ca", "छ": "cha", "ज": "ja", "झ": "jha", "ञ": "ña", "ट": "ṭa", "ठ": "ṭha", "ड": "ḍa", "ढ": "ḍha", "ण": "ṇa", "त": "ta", "थ": "tha", "द": "da", "ध": "dha", "न": "na", "प": "pa", "फ": "pha", "ब": "ba", "भ": "bha", "म": "ma", "य": "ya", "र": "ra", "ल": "la", "व": "va", "श": "śa", "ष": "ṣa", "स": "sa", "ह": "ha", "ळ": "ḷa", "ॐ" : "ōm̐", "a": "a", "b": "b", "c": "c", "d": "d", "e": "e", "f": "f", "g": "g", "h": "h", "i": "i", "j": "j", "k": "k", "l": "l", "m": "m", "n": "n", "o": "o", "p": "p", "q": "q", "r": "r", "s": "s", "t": "t", "u": "u", "v": "v", "w": "w", "x": "x", "y": "y", "z": "z", "A": "A", "B": "B", "C": "C", "D": "D", "E": "E", "F": "F", "G": "G", "H": "H", "I": "I", "J": "J", "K": "K", "L": "L", "M": "M", "N": "N", "O": "O", "P": "P", "Q": "Q", "R": "R", "S": "S", "T": "T", "U": "U", "V": "V", "W": "W", "X": "X", "Y": "Y", "Z": "Z", "॰":".", "\u0951":"ˆ", "\u0952":"̱", "\u0952":"\u0332", "\u1CF5":"\u1CF5", "\u1CF6":"\u1CF6", "\uA8EB":"\u0301" };

    const swaras = ["अ", "आ", "ॲ", "ऑ", "इ", "ई", "उ", "ऊ", "ऋ", "ॠ", "ऌ", "ॡ", "ऍ", "ऎ", "ए", "ऐ", "ऒ", "ओ", "औ"];

    const diacritics = { "्": " ", "ा": "ā", "ॅ": "ê", "ॉ": "ô", "ि": "i", "ी": "ī", "ु": "u", "ू": "ū", "ृ": "r̥", "ॄ": "r̥̄", "ॢ": "l̥", "ॣ": "l̥̄", "ॆ": "e", "े": "ē", "ै": "ai", "ॊ": "o", "ो": "ō", "ौ": "au", "ं": "ṁ", "ः": "ḥ", "़": "ʾ", "ँ": "m̐", "ऽ": "'" };

    const gutturalLetter = ["क", "ख", "ग", "घ"];
    const palatalLetter = ["च", "छ", "ज", "झ"];
    const retroflexLetter = ["ट", "ठ", "ड", "ढ"];
    const dentalLetter = ["त", "थ", "द", "ध"];
    const labialApproximateLetter = ["प", "फ", "ब", "भ", "य", "र", "व", "श", "ष", "स", "ह"];

    const nonPronunced = ["्", "ा", "ॅ", "ॉ", "ि", "ी", "ु", "ू", "ृ", "ॄ", "ॢ", "ॣ", "ॆ", "े", "ै", "ॊ", "ो", "ौ", "़" , "ऽ"];

    const anunasika = { "a": "ã", "ā": "ā̃", "i": "ĩ", "ī": "ī̃", "u": "ũ", "ū": "ū̃", "r̥": "r̥̃", "r̥̄": "ṝ̃", "e": "ẽ", "ē": "ē̃", "o": "õ", "ō": "ō̃" };

    let resultLa = "";
    
    for (let u = 0; u < input.length; u++) {
      if (input[u] && diacritics[input[u]] && nonPronunced.indexOf(input[u]) > -1 && input[u - 1] && swaras.indexOf(input[u - 1]) > -1) {
        let ulpaswara1 = ['ॠ', 'ॡ'];
        let ulpaswara2 = ['ऋ', 'ऌ', 'ऐ', 'औ'];
        if (ulpaswara1.indexOf(input[u - 1]) > -1)
          resultLa = resultLa.slice(0, -3);
        else if (ulpaswara2.indexOf(input[u - 1]) > -1)
          resultLa = resultLa.slice(0, -2);
        else
          resultLa = resultLa.slice(0, -1);
        continue;
      } else if (input[u] != " " && diacritics[input[u]] && input[u] == "ಁ") {
        let lastVowel = resultLa[resultLa.length - 1];
        if (anunasika[lastVowel])
          resultLa = resultLa.slice(0, -1) + anunasika[lastVowel];
        else
          resultLa = resultLa + "m̐";
      } else if (input[u] != " " && diacritics[input[u]] && input[u - 1] != "अ") {
        if (input[u] != " " && diacritics[input[u - 1]] && diacritics[input[u]]) {
          resultLa = resultLa + diacritics[input[u]];
        } else if (input[u] == "्") {
          resultLa = resultLa.slice(0, -1);
        } else {
          if (input[u] == "ं" || input[u] == "ः") { // Anusvara & Visarga
            if (input[u - 1] && swaras.indexOf(input[u - 1])) {
              resultLa = resultLa + diacritics[input[u]];
            } else {
              resultLa = resultLa.slice(0, -1) + 'a' + diacritics[input[u]];
            }
          } else {
            // Nukta signs in Devanagari
            if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "क़") {
              resultLa = resultLa.slice(0, -2) + "q";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "ख़") {
              resultLa = resultLa.slice(0, -3) + "k͟h";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "ग़") {
              resultLa = resultLa.slice(0, -2) + "ġ";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "ज़") {
              resultLa = resultLa.slice(0, -2) + "z";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "झ़") {
              resultLa = resultLa.slice(0, -3) + "ž";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u]) == "ड़") {
              resultLa = resultLa.slice(0, -2) + "ṛ";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "ढ़") {
              resultLa = resultLa.slice(0, -3) + "ṛh";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "त़") {
              resultLa = resultLa.slice(0, -3) + "t̤";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "थ़") {
              resultLa = resultLa.slice(0, -3) + "s̱";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "फ़") {
              resultLa = resultLa.slice(0, -3) + "f";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "व़") {
              resultLa = resultLa.slice(0, -2) + "w";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "स़") {
              resultLa = resultLa.slice(0, -3) + "s̤";
            } else if (input[u] == "़" && input[u - 1] && (input[u - 1] + input[u])  == "ह़") {
              resultLa = resultLa.slice(0, -3) + "h̤";
            } else {
              resultLa = resultLa.slice(0, -1) + diacritics[input[u]];
            }
          }
        }
      } else if (input[u - 1] == "अ" && diacritics[input[u]] && nonPronunced.indexOf(input[u]) == -1) {
        resultLa = resultLa.slice(0, -1) + devanagariToLatin[input[u - 1] + input[u]];
      } else if (input[u].indexOf("\n") > -1) {
        resultLa = resultLa + "\n";
      } else if (devanagariToLatin[input[u]] != undefined && devanagariToLatin[input[u]] != null && input[u] != "") {
        // Anusvara rule
        if (input[u - 1] && input[u - 1] == "ं" && gutturalLetter.indexOf(input[u]) > -1) {
          resultLa = resultLa.slice(0, -1) + "ṅ" + devanagariToLatin[input[u]];
        } else if (input[u - 1] && input[u - 1] == "ं" && palatalLetter.indexOf(input[u]) > -1) {
          resultLa = resultLa.slice(0, -1) + "ñ" + devanagariToLatin[input[u]];
        } else if (input[u - 1] && input[u - 1] == "ं" && retroflexLetter.indexOf(input[u]) > -1) {
          resultLa = resultLa.slice(0, -1) + "ṇ" + devanagariToLatin[input[u]];
        } else if (input[u - 1] && input[u - 1] == "ं" && dentalLetter.indexOf(input[u]) > -1) {
          resultLa = resultLa.slice(0, -1) + "n" + devanagariToLatin[input[u]];
        } else if (input[u - 1] && input[u - 1] == "ं" && labialApproximateLetter.indexOf(input[u]) > -1) {
          resultLa = resultLa.slice(0, -1) + "m" + devanagariToLatin[input[u]];
        } else if (input[u - 1] && input[u - 1] == "ं" && gutturalLetter.indexOf(input[u]) == -1 && palatalLetter.indexOf(input[u]) == -1 && retroflexLetter.indexOf(input[u]) == -1 && dentalLetter.indexOf(input[u]) == -1 && labialApproximateLetter.indexOf(input[u]) == -1 && input[u] == " ") {
          resultLa = resultLa.slice(0, -1) + "ṁ" + devanagariToLatin[input[u]];
        } else {
          resultLa = resultLa + devanagariToLatin[input[u]];
        }
      }
    }
    return resultLa;
  }
  return "";
}

module.exports = sanskrittransliterate;
/*  
  Devanagari : https://en.wikipedia.org/wiki/Devanagari
  Devanagari Transliterations : https://en.wikipedia.org/wiki/Devanagari_transliteration
  Devanagari Unicode Block : https://www.unicode.org/charts/PDF/U0900.pdf
  Devanagari Extended Unicode Block : https://www.unicode.org/charts/PDF/UA8E0.pdf
  Devanagari Extended-A Unicode Block : https://www.unicode.org/charts/PDF/U11B00.pdf
  Vedic Extensions Unicode Block : https://www.unicode.org/charts/PDF/U1CD0.pdf  
    Vedic accents : https://doi.org/10.5281/zenodo.837826
*/
