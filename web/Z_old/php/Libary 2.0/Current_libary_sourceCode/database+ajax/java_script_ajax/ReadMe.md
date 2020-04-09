/******************************
The main goals of this project
******************************/
    The main goals of this project are the following

    1. To make ajax requests easier to implement.
    2. To make ajax requests more generic and reuseable.
    3. To maximize browser support (***fetch has less browser support***).
    4. To Reduce Dependacy on libaries like Jquery (***that slow down your site or project***).
    5. To more easily send json encoded strings to php.

/**********
Basic info
**********/

    INTRO
    This project is build using the singleton pattern AKA the module pattern.
    This means that its basicly an object with 4 public available functions.

    OBJECTNAME
    The objectname = ajax_module

    FUNCTION NAMES
    and the 4 object function names are
    1. get
    2. post
    3. putJson
    4. postFile

    BASIC USE EXPLAINATION
    To use these you need to call the object and then the function like
    ajax_module.get(var1 var2 var3 var4);

/**************************
Functions use explaination
**************************/

    1. get      => explaination on line 45-80
    2. post     => explaination on line 82-120
    3. putJson  => explaination on line 120-160
    4. postFile => explaination on line (***not yet working so no explaination***);



    /***
    GET
    ***/
        The get request expects the following variables
        (url, selectorObject, callbackFunc, cache)

        **url**
            which file the request is send to.

        **selectorObject**
            selectorObject an object that contains the data you want to add to the url.

            for example with the following object => {id:1, name:2};
            will be converted in => ?id=1&name=2;

            this will be added to the url when making the request

        **callbackFunc**
            This is your own coded callback function which you want to run after the requests
            this one you need to format like a variable for example

            const mycallbackfunc = function(response) {
                alert(response)
            }

            This function will alert the response the server has given.

            ***its recommended to always let your server respond otherwise this script will keep testing for around 30 seconds before timing out.***

        **cache**
            if this is 1 the request header => Cache-Control: no-cache
            will be set.
            If it isn't set or is something other then 1 this will do nothing

            This feature can be useful for debugging or if you need to send every request to the server.
            instead of using the local stored cache

    /****
    POST
    ****/
        The post request expects the following variables
        (url, selectorObject, callbackFunc, payload, cache)

        **url**
            which file the request is send to.

        **selectorObject**
            selectorObject an object that contains the data you want to add to the url.

            for example with the following object => {id:1, name:2};
            will be converted in => ?id=1&name=2;

            this will be added to the url when making the request

        **callbackFunc**
            This is your own coded callback function which you want to run after the requests
            this one you need to format like a variable for example

            const mycallbackfunc = function(response) {
                alert(response)
            }

            This function will alert the response the server has given.

            ***its recommended to always let your server respond otherwise this script will keep testing for around 30 seconds before timing out.***

        **payload**
            This need to be an object which will be encoded and posted like a regular form would

        **cache**
            if this is 1 the request header => Cache-Control: no-cache
            will be set.
            If it isn't set or is something other then 1 this will do nothing

            This feature can be useful for debugging or if you need to send every request to the server.
            instead of using the local stored cache

    /*******
    PUTJSON
    *******/
        The post request expects the following variables
        (url, selectorObject, callbackFunc, payload, cache)

        **url**
            which file the request is send to.

        **selectorObject**
            selectorObject an object that contains the data you want to add to the url.

            for example with the following object => {id:1, name:2};
            will be converted in => ?id=1&name=2;

            this will be added to the url when making the request

        **callbackFunc**
            This is your own coded callback function which you want to run after the requests
            this one you need to format like a variable for example

            const mycallbackfunc = function(response) {
                alert(response)
            }

            This function will alert the response the server has given.

            ***its recommended to always let your server respond otherwise this script will keep testing for around 30 seconds before timing out.***

        **payload**
            This need to be something that can be converted to a Json string like an array or an object

        **cache**
            if this is 1 the request header => Cache-Control: no-cache
            will be set.
            If it isn't set or is something other then 1 this will do nothing

            This feature can be useful for debugging or if you need to send every request to the server.
            instead of using the local stored cache
