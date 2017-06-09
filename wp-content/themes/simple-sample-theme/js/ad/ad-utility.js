//A Library of (hopefully) reusable js functions

/*|||||||||||||||||||||||||
 |||| DOM Manipulation |||||
 |||||||||||||||||||||||||*/

//given a query selector string, it checks to see if the selector is an id and if so it uses a different method to selcet that element
//The Good: Apparently using a getElementById is faster than just querySelectorAll
//The Bad : The output of this function is a statement and the output of that statement will differ based on what method it uses
// i.e querySelectorAll returns a nodelist which will have to be looped through with a for loop(map isn't supported) while getElementById returns just the element?

function vanillaQuerySelection(selector) {
var selectorType = 'querySelectorAll'; //will return a nodelist
        //check to see if we are checking an id
        if (selector.indexOf('#') === 0) {
selectorType = 'getElementById'; //will return an element
        selector = selector.substr(1, selector.length);
}
//console.log(document[selectorType](selector));
return document[selectorType](selector);
        }

//function that creates html elements as strings. does not allow for self closing elements or empty elements
//uses this object syntax to add html attributes 
//     var SampleAttributeAy = [
//         {attr : 'href',    attrVal : 'https://www.google.com'},
//         {attr : 'title',   attrVal : 'Google Search Engine'}
//     ];

function adFillElement(elem, filler, optionalClass, optionalAttributeArray) {
if ((Number.isInteger(filler)) || (filler == (undefined || null))) {
//eventually do some checks that will allow for <br>, <img>, <hr>, etc
return '';
}
var optAttributes = optionalAttributeArray;
        var elementAttributes = ' ';
        //Checking for valid input
        if ((!Array.isArray(optAttributes)) || (optAttributes == (null || undefined))) {
optAttributes = '';
        elementAttributes = '';
} else {
optAttributes.map(function (x) {
var fullElement = x.attr + '="' + x.attrVal + '" ';
        elementAttributes += fullElement;
});
}
var optClass = optionalClass;
        if ((optClass == ((null || undefined || ''))) || ((Number.isInteger(optClass)))) {
optClass = '';
} else {
optClass = ' class=" ' + optionalClass + ' "';
}
return '<' + elem + optClass + elementAttributes + '>' + filler + '</' + elem + '>';
        }


//Add or remove spellcheking to/from a DOM element
//Given a query selector string or array of them, this function will add or remove the spellcheck attribute to/from that element
//ex. 1 spelChek('.firstName', true);
//ex. 2 spelChek(['.lastName', '.favoriteFood', '#message'], false)
//ex. 3 spelCheck(arrayOfInputs);
//ex. 4 spelCheck('', false);
function spelCheck(elementOrElements, checkSpelling) {
//console.log('Welcome to the spelcheck function');
//See if the intended use is to add or remove spellcheck feature
var addSpellChecking = 'true';
        if (!checkSpelling) {
addSpellChecking = 'false';
}
if (typeof checkSpelling === 'undefined') {
//default is set to adding spellchecking
addSpellChecking = 'true';
}
//console.log("We will be setting the spellcheck attribute to " + addSpellChecking);
//let's hope they aren't using i.e.
spellcheckAttributeSupported = true;
        //If spellcheck attribute is supported, do it the easy way...
        if (spellcheckAttributeSupported) {
//console.log('Your browser supports the spellcheck attribute');

//check to see what the argument is...
if ((typeof elementOrElements === 'undefined') || (elementOrElements === '')) {
//no argument is passed, set the default value to be all inputs in the DOM
elementOrElements = 'input';
        var defaultSelection = document.getElementsByTagName(elementOrElements);
        //and then use a different method to grab the elements
        for (var j = 0; j < defaultSelection.length; j++) {
defaultSelection[j].setAttribute('spellcheck', addSpellChecking);
}
return;
} else if (Array.isArray(elementOrElements)) {
elementOrElements.map(function (x) {
addSpellCheckAttribute(x);
});
} else {
//if it's not undefined and it's not an array, let's hope that it's a valid query selector string
elementOrElements = elementOrElements.toString();
        addSpellCheckAttribute(elementOrElements);
}
}
//If spellchecking isn't suported in the browser, do something here?
if (!spellcheckAttributeSupported) {
console.warn('Your browser does NOT support the spellcheck attribute');
}
function addSpellCheckAttribute(querySelectorString) {
var currentQuerySelection = document.querySelectorAll(querySelectorString);
        //console.log(querySelectorString);
        //console.log(currentQuerySelection);
        //can't use map method because query selctor returns a node list which doesn't act like a normal array
        for (var i = 0; i < currentQuerySelection.length; i++) {
currentQuerySelection[i].setAttribute('spellcheck', addSpellChecking);
}
}//end addSpellCheckAttribute
}//end spelCheck


//query string selector fn from https://stackoverflow.com/questions/979975/how-to-get-the-value-from-the-get-parameters
var QueryString = function () {
// This function is anonymous, is executed immediately and 
// the return value is assigned to QueryString!
var query_string = {};
        var query = window.location.search.substring(1);
        var vars = query.split("&");
        for (var i = 0; i < vars.length; i++) {
var pair = vars[i].split("=");
        // If first entry with this name
        if (typeof query_string[pair[0]] === "undefined") {
query_string[pair[0]] = decodeURIComponent(pair[1]);
        // If second entry with this name
} else if (typeof query_string[pair[0]] === "string") {
var arr = [query_string[pair[0]], decodeURIComponent(pair[1])];
        query_string[pair[0]] = arr;
        // If third or later entry with this name
} else {
query_string[pair[0]].push(decodeURIComponent(pair[1]));
}
}
return query_string;
        }();
// bd add click event fn
        $('.section-block, .related-cs, .event-block, .sp, .locations .location').click(function () {
var anchor = $(this).find("a");
        if (anchor.hasClass('new-tab')) {
window.open($(this).find("a").attr("href"), '_blank');
} else {
window.location = $(this).find("a").attr("href");
}

return false;
        });
//parent event listener
        /** @function helicopterParent
         *   @param {string} parentName - a valid query selector string
         *   @param {string} functionName - the function that will be eecuted when the child is found
         *   @param {array} functionArgs - an array of arguments to pass to the function
         *   @returns {undefined} - procedural!
         *   @description creates an event listener for a parent div and if any of it's children are clicked it will execute  a function
         */
                function helicopterParent(parentName, functionName, functionArgs) {
                var theParent = document.querySelector(parentName);
                        theParent.addEventListener("click", doSomething, false);
                        function doSomething(e) {
                        if (e.target !== e.currentTarget) {
                        var clickedItem = e.target.id;
                                //var clickedItemData = e.target.dataset.adFpUnitid;
                                //alert("Hello there " + clickedItem);
                                functionName.apply(this, functionArgs)
                        }
                        e.stopPropagation();
                        }
                }

//var theParent = document.querySelector("#spMap");
//theParent.addEventListener("click", doSomething, false);
//
//function doSomething(e) {
//    if (e.target !== e.currentTarget) {
//        var clickedItem = e.target.id;
//        clickedItem = e.target.dataset.adFpUnitid;
//        //alert("Hello there " + clickedItem);
//        viewLarge(clickedItem);
//    }
//    e.stopPropagation();
//}


        /** @function getURLparam
         *   @param {string} paramString - the string that you are looking for in the url
         *   @returns {string} - returns the value of the parameter string?
         *   @description return the rest of the url after the value you are searching for is found
         */
        function getURLparam(paramString) {
        var paramValue = '';
                var param = paramString;
                var myHref = window.location.href;
                var paramPosition = window.location.href.search(param); //search the ref for the position of the parameter you are searching for
                //return if the param does not exist
                if (paramPosition === - 1) {
        return console.warn(paramString + ' does not exist in the url?');
        }
        //get the value of the string from the end of the location of the parameter name to the end of the url. Eventually add regex to find whteher or not there are multiple params ?
        var paramValue = window.location.href.substr(paramPosition + param.length, myHref.length);
                return paramValue;
        }

     