// function parser(){
//     console.log("waht you do: ")
//     const str = 'Some text \\dfrac{1}{2} and \\frac{3}{4} with fractions \\dfrac{5}{6} and \\frac{7}{8}.';
//     const str2 = 'Some text \dfrac{1}{2} and \frac{3}{4} with fractions \dfrac{5}{6} and \frac{7}{8}.';
//     console.log(str.length + "   " + str);
//     console.log(str2.length + "   " + str2);
//     const regex = /\\dfrac|\\frac/g;
//     const matches = [];
//     let match;
//
//     while ((match = regex.exec(str)) !== null) {
//         console.log('vosiel som');
//         matches.push(match.index);
//     }
//
//     console.log(matches);
// }
// function test1 (){
//     console.log("waht you do: ")
//     const str = 'Some text \dfrac{1}{2} and \frac{3}{4} with fractions \dfrac{5}{6} and \frac{7}{8}. Some more \left(\right) examples.';
//     console.log(str);
//     const modifiedStr = str.replace(/\\dfrac|\\frac|\\left|\\right/g, '\\$&');
//     console.log(modifiedStr);
// }
//
// function test2 (){
//     console.log("waht you do: ")
//     const str = 'Some text \\dfrac{1+2}{3-4} and \\dfrac{x}{y} with fractions.';
//     const str2 = '\\left[ \\dfrac{3}{2}-\\dfrac{3}{2}e^{-\\frac{2}{5}(t-4)}-\\dfrac{3}{5}(t-4)e^{-\\frac{2}{5}(t-4)} \\right] \\eta(t-4)'
//     const regex = /\\dfrac\{([^{}]+)\}\{([^{}]+)\}/g;
//     let match;
//
//     while ((match = regex.exec(str)) !== null) {
//         const matchStart = match.index;
//         const firstBraceStart = matchStart + match[0].indexOf('{') + 1;
//         const firstBraceEnd = matchStart + match[0].indexOf('}') + 1;
//         const secondBraceStart = matchStart + match[0].lastIndexOf('{') + 1;
//         const secondBraceEnd = matchStart + match[0].lastIndexOf('}') + 1;
//
//         console.log("First Brace Start:", firstBraceStart);
//         console.log("First Brace End:", firstBraceEnd);
//         console.log("Second Brace Start:", secondBraceStart);
//         console.log("Second Brace End:", secondBraceEnd);
//     }
// }
// function test3(){
//     console.log("waht you do: prepis vsetky frac na dfrac a vytiahni ich")
//     //const str = 'Some text \\frac{1+\\dfrac{2}{3}}{4-5-\\frac{2}{3}} and \\frac{x}{y} with fractions.';
//     //const str2 = '4\\dfrac{3s+1}{s^3+10s^2+13s+14}';
//     const str3 = '\\left[ \\dfrac{3}{2}-\\dfrac{3}{2}e^{-\\frac{2}{5}(t-4)}-\\dfrac{3}{5}(t-4)e^{-\\frac{2}{5}(t-4)} \\right] \\eta(t-4)'
//     //change frac to dfrac
//     const regex1 = /\\frac(?!\{[^{}]*\\dfrac[^{}]*})(?=\{)/g;
//     const result = str3.replace(regex1, '\\dfrac');
//     //console.log(str);
//     console.log("original: " + str3);
//     console.log("after replacements: " + result);
//
//
//
//     const regex = /\\dfrac{((?:[^{}]+|{(?:[^{}]+|{[^{}]*})*})*)}{((?:[^{}]+|{(?:[^{}]+|{[^{}]*})*})*)}/g;
//     let match;
//     let newString = ""
//     while ((match = regex.exec(result)) !== null) {
//         console.log("what you do: prechadza vsetky vyskity dfrac a vypisuje obsah ich zatvoriek")
//         //newString += match[0]
//         const firstContent = match[1];
//         const secondContent = match[2];
//         console.log(match.length);
//         console.log("whole string: " , match[0]);
//         console.log("First Content:", firstContent);
//         console.log("Second Content:", secondContent);
//         // console.log("Third Content:", match[3]);
//         const newSubString = "(" + firstContent + ")/(" + secondContent + ")";
//         console.log("newSubString: " + newSubString);
//
//     }
//     //console.log("new string: " + newString);
//     console.log("updated String: " + result);
// }
//
// function test4(index) {
//     console.log("What you do: replace all frac with dfrac and extract their contents");
//     const str3 = index;
//
//     // Change frac to dfrac
//     const regex1 = /\\frac(?!\{[^{}]*\\dfrac[^{}]*})(?=\{)/g;
//     var result = str3.replace(regex1, '\\dfrac');
//     //replace [] with ()
//     result = result.replace('\\left[', '(');
//     result = result.replace('\\right]', ')');
//     console.log("Original: " + str3);
//     console.log("After replacements: " + result);
//
//     const regex = /\\dfrac{((?:[^{}]+|{(?:[^{}]+|{[^{}]*})*})*)}{((?:[^{}]+|{(?:[^{}]+|{[^{}]*})*})*)}/g;
//     let match;
//     let updatedString = result;
//
//     while ((match = regex.exec(result)) !== null) {
//         console.log("What you do: process each dfrac and replace it with newSubString");
//
//         const firstContent = match[1];
//         const secondContent = match[2];
//         console.log("Whole string: ", match[0]);
//         console.log("First Content:", firstContent);
//         console.log("Second Content:", secondContent);
//
//         const newSubString = "(" + firstContent + ")/(" + secondContent + ")";
//         console.log("newSubString: " + newSubString);
//
//         updatedString = updatedString.replace(match[0], newSubString);
//     }
//
//     console.log("Updated String: " + updatedString);
// }
function trimEquation(index){
    var result = index;
    const parts = result.split("=");
    if (parts.length > 1) {
        result = parts[1].trim();

    }
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function replaceBraces(index){
    var result = index;
    result = result.replace(/\\left\[/g, '(');
    result = result.replace(/\\right\]/g, ')');
    result = result.replace(/\\left\(/g, '(');
    result = result.replace(/\\right\)/g, ')');
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function replaceFrac2Dfrac(index){
    var result = index;
    const regex1 = /\\frac(?!\{[^{}]*\\dfrac[^{}]*})(?=\{)/g;
    result = result.replace(regex1, '\\dfrac');
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function replaceRoot2Norm(index){
    var result = index;
    var equation = "\\sqrt[s+5]{64+\\sqrt{64}} + \\sqrt{64}";
    const regex = /\\sqrt{/g;
    result = result.replace(regex, '\\sqrt[2]{');

    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function replaceRoot(index){
    var result = index;
    const regex1 = /\\sqrt\[(.*?)\]{(.*?)}/g;
    result = result.replace(regex1, "($2)**(1/($1))");
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function changeDfrac2Normal(index){
    var result = index;
    const regex = /\\dfrac{((?:[^{}]+|{(?:[^{}]+|{[^{}]*})*})*)}{((?:[^{}]+|{(?:[^{}]+|{[^{}]*})*})*)}/g;
    let match;
    let updatedString = result;

    while ((match = regex.exec(result)) !== null) {
        console.log("What you do: process each dfrac and replace it with newSubString");

        var firstContent = match[1];
        if(firstContent.indexOf("\dfrac")){
            console.log("obsahuje substring 1: " + firstContent);
            firstContent = changeDfrac2Normal(firstContent);
        }
        var secondContent = match[2];
        if(secondContent.indexOf("\dfrac")){
            console.log("obsahuje substring 2: " + secondContent);
            secondContent = changeDfrac2Normal(secondContent);
        }
        // console.log("Whole string: ", match[0]);
        const newSubString = "(" + firstContent + ")/(" + secondContent + ")";
        console.log("newSubString: " + newSubString);
        updatedString = updatedString.replace(match[0], "(" + newSubString + ")");
    }
    console.log("Original: " + index);
    console.log("Updated String: " + updatedString);
    return updatedString;
}
function replacePower(index){
    var result = index;
    result = result.replace(/\^/g, '**');
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function replaceRemainingBraces(index){
    var result = index;
    result = result.replace(/\{/g, '(');
    result = result.replace(/\}/g, ')');
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function replaceEta(index){
    var result = index;
    result =result.replace(/\\eta/g, '8');
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function replaceE(index){
    var result = index;
    result =result.replace(/e/g, '2.7183');
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}
function addMultiply(index){
    var result = index;
    result = result.replace(/ /g, '');
    result = result.replace(/\)\(/g, ')*(');
    result = result.replace(/(\d)\(/g, '$1*(');
    result = result.replace(/\)(\d)/g, ')*$1');
    result = result.replace(/\)([a-zA-Z])/g, ')*$1');
    result = result.replace(/(\d)([a-zA-Z])/g, '$1*$2');
    console.log("Original: " + index);
    console.log("After replacements: " + result);
    return result;
}



//TODO tuto funkciu aplikuj na odpoved a aj na vysledok
function final(index){
    try {
        var result = index;
        result = trimEquation(result);
        result = replaceBraces(result);
        result = replaceFrac2Dfrac(result);
        result = replaceRoot2Norm(result);
        result = replaceRoot(result);
        result = changeDfrac2Normal(result);
        result = replacePower(result);
        result = replaceRemainingBraces(result);
        result = replaceEta(result);
        result = addMultiply(result);
        result = replaceE(result);
        result = addMultiply(result);
        console.log("result: " + result);


        return result;
    }
    catch (e){
        console.log("final exeption: ");
        return "";
    }
}
//TODO odpovede z predchazajucich daj do tejto funkcie, ak sa rovnaju vrati true a ak nie tak vrati false, v pripade chyby vrati false
function evaluate(solution, answer) {
    let vysledok = solution;
    let odpoved = answer;
    vysledok = vysledok.replace(/[st]/g, "3");
    odpoved = odpoved.replace(/[st]/g, "3");
    try {
        let vysledok1 = eval(vysledok);
        let odpoved1 = eval(odpoved);
        vysledok1 = vysledok1.toFixed(3);
        odpoved1 = odpoved1.toFixed(3);
        console.log("vysledok: " + vysledok1 + " : " + odpoved1);
        if (vysledok1 === odpoved1) {
            console.log("true1");
            return true;
        } else {
            console.log("false1");
            return false;
        }
    } catch (e) {
        console.log("exeption");
        return false;
    }
}
function dokoncenie() {
    let solution = final("y(t)=\\left[ \\dfrac{3}{4}-\\dfrac{3}{4}e^{-\\frac{4}{5}(t-7)}-\\dfrac{3}{5}(t-7)e^{-\\frac{4}{5}(t-7)} \\right] \\eta(t-7)");
    let answer = final("\\left[ \\dfrac{7}{5}-\\dfrac{7}{5}e^{-\\frac{5}{2}(t-6)}-\\dfrac{7t}{2}(t-6)e^{-\\frac{15}{2}(t-6)} \\right] \\eta(2t-6)");

    let vysledok = evaluate(solution, answer);
    console.log("odpoved: " + solution + " : " + answer);
    return vysledok;
}
// console.log(2 * 3 ** 3)
// console.log(((3)/(2)-((9)/(8))/(2)*2.7183**(-(2)/(5)*(3-4))-(3)/(5)*(3-4)*2.7183**(-(2)/(5)*(3-4)))*(8*(3-4)))
// console.log(((3)/(2)-((9)/(8))/(2)*2.7183**(-(2)/(5)*(3-4))-(3)/(5)*(3-4)*2.7183**(-(2)/(5)*(3-4)))*8*(3-4))

// "\\dfrac{2s+ 1}{6s +4}"
// "\\dfrac{s + 0.5}{3s + 2}"
// "\\frac{0.5s + 0.25}{1.5s +1}"
// "4\\dfrac{3s+\\dfrac{s}{s}}{s^3+10s^2+13s+14s^0}"


