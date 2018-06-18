// which diceware language list will be used to lookup words.
var currentList = 'eff'
// an array of objects representing the current random word list.
var wordList = []
// the running tally of total entropy in the wordList
var totalEntropy = new Big(0)

// set password fields
var $password = $('#password');
var $passwordConfirm = $('#password_confirm');
var specialCharacters = ['.', '@', '#', '$', '%', '^', '|', '?', '*', '!', ':', ';', '-', '+', '&', '=', '{', '}', '[', ']', '\\', '/'];

// Takes an array of word objects and display them on the page.
function displayWords (words) {
    'use strict'

    // add the word to the main display
    $.each(words, function (index, obj) {
        $password.val($password.val() + obj.word.capitalize())
        $passwordConfirm.val($passwordConfirm.val() + obj.word.capitalize())
        var $specialChar = specialCharacters[Math.floor(Math.random() * specialCharacters.length)];
        if (index != (words.length - 1)) {
            var $rand = ~~(Math.random() * 10)
            $password.val($password.val() + $specialChar + $rand)
            $passwordConfirm.val($passwordConfirm.val() + $specialChar + $rand)
        }
    })
}

// Returns an array of objects of length numWords (default 1).
// Each object in the array represents a word and its index
// and is the result of numRollsPerWord die rolls (default 5).
function getWords (numWords, numRollsPerWord) {
    'use strict'

    var i,
        j,
        words,
        rollResults,
        rollResultsJoined

    words = []

    if (!numWords) { numWords = 1 }
    if (!numRollsPerWord) { numRollsPerWord = 5 }

    for (i = 0; i < numWords; i += 1) {
        rollResults = []

        for (j = 0; j < numRollsPerWord; j += 1) {
            // roll a 6 sided die
            rollResults.push(secureRandom(6) + 1)
        }

        rollResultsJoined = rollResults.join('')
        words.push(getWordFromWordNum(rollResultsJoined)[0])
    }

    return words
}

// See : https://www.reddit.com/r/crypto/comments/4xe21s/
//
// skip is to make result in this range:
// 0 ≤ result < n* count < 2^31
// (where n is the largest integer that satisfies this equation)
// This makes result % count evenly distributed.
//
// P.S. if (((count - 1) & count) === 0) {...} is optional and for
// when count is a nice binary number (2n). If this if statement is
// removed then it might have to loop a few times. So it saves a
// couple of micro seconds.
function secureRandom (count) {
    var cryptoObj = window.crypto || window.msCrypto
    var rand = new Uint32Array(1)
    var skip = 0x7fffffff - 0x7fffffff % count
    var result

    if (((count - 1) & count) === 0) {
        cryptoObj.getRandomValues(rand)
        return rand[0] & (count - 1)
    }

    do {
        cryptoObj.getRandomValues(rand)
        result = rand[0] & 0x7fffffff
    } while (result >= skip)

    return result % count
}

// Lookup a word by its wordNum and return
// an Array with a single word object suitable for displayWords.
function getWordFromWordNum (wordNum) {
    if (wordNum.length === 5) {
        var word
        switch (currentList) {
            case 'alternative':
                word = alternative[wordNum]
                break
            case 'catalan':
                word = catalan[wordNum]
                break
            case 'danish':
                word = danish[wordNum]
                break
            case 'diceware':
                word = diceware[wordNum]
                break
            case 'dutch':
                word = dutch[wordNum]
                break
            case 'esperanto':
                word = esperanto[wordNum]
                break
            case 'finnish':
                word = finnish[wordNum]
                break
            case 'french':
                word = french[wordNum]
                break
            case 'german':
                word = german[wordNum]
                break
            case 'japanese':
                word = japanese[wordNum]
                break
            case 'maori':
                word = maori[wordNum]
                break
            case 'norwegian':
                word = norwegian[wordNum]
                break
            case 'polish':
                word = polish[wordNum]
                break
            case 'swedish':
                word = swedish[wordNum]
                break
            case 'spanish':
                word = spanish[wordNum]
                break
            default:
                word = eff[wordNum]
                break
        }
        return [{'word': word, 'wordNum': wordNum, 'entropy': calcEntropyForWordOrSymbol(false)}]
    } else if (wordNum.length === 2) {
        return [{'word': special[wordNum], 'wordNum': wordNum, 'entropy': calcEntropyForWordOrSymbol(true)}]
    }
}


// See : http://world.std.com/~reinhold/dicewarefaq.html#calculatingentropy
function calcEntropyForWordOrSymbol (isSymbol) {
    var entropy

    if (!isSymbol) {
        // ~ 12.9 bit of entropy per Diceware word.
        entropy = new Big(Math.log2(7776))
    } else {
        // ~ 5.16 bits for special characters.
        entropy = new Big(Math.log2(36))
    }

    return entropy
}

// uppercase first letter
String.prototype.capitalize = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
};


!function ($) {

    $(function() {

        'use strict';

        // The nav links are used to select the current word list.
        $('.js-genWordsButton').on('click', function (e) {
            $password.val('');
            $passwordConfirm.val('');
            var numWords, numRolls, reset;
            numWords = 5;
            numRolls = 5;
            displayWords(getWords(numWords, numRolls));
            //displayCrackTime(wordList)

            if ($("#password").val().length !== 0 && $password.attr('type') == "text" && $(".js-copy-to-clipboard").length === 0) {
                $(".js-password-btn-group").append('<a href="javascript:" class="btn btn-default js-copy-to-clipboard" data-clipboard-target="#password">Copy</a>');
            }else{

            }
        });

        // show or hide password with copy function
        $(".js-show-pwd").on('click', function(){
            if ($password.attr('type') == "text") {
                $password.attr('type', 'password');
                $(this).text(LANG.button_show_txt);
                $(".js-copy-to-clipboard").remove();
            }else{
                $password.attr('type', 'text');
                $(this).text(LANG.button_hide_txt);
                //$(".js-copy-to-clipboard").show();
                if ($("#password").val().length !== 0 && $(".js-copy-to-clipboard").length === 0) {
                    $(".js-password-btn-group").append('<a href="javascript:" class="btn btn-default js-copy-to-clipboard" data-clipboard-target="#password">'+ LANG.button_copy_txt +'</a>');
                }
            }
        });

        var clipboard = new Clipboard('.js-copy-to-clipboard');

        clipboard.on('success', function(e) {
            /*console.info('Action:', e.action);
            console.info('Text:', e.text);
            console.info('Trigger:', e.trigger);*/
            toastr.success(e.action + ' ' + LANG.copy_to_clipboard_ok);

            e.clearSelection();
        });

        clipboard.on('error', function(e) {
            /*console.error('Action:', e.action);
            console.error('Trigger:', e.trigger);*/
            toastr.error(e.action + ' ' + LANG.copy_to_clipboard_fail);
        });

    });

}(window.jQuery);