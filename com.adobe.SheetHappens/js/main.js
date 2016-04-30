/*jslint vars: true, plusplus: true, devel: true, nomen: true, regexp: true, indent: 4, maxerr: 50 */
/*global $, window, location, CSInterface, SystemPath, themeManager*/

(function () {
    'use strict';

    var csInterface = new CSInterface();


    function init() {
        themeManager.init();

        $("#btn_placing").click(function () {
            csInterface.evalScript('placing()');
        });
        $("#btn_resizing").click(function () {
            csInterface.evalScript('startCloneandResizing()');
        });
        $("#btn_borders").click(function () {
            csInterface.evalScript('createBorders()');
        });

        $("#btn_sendsheets").click(function () {
            csInterface.evalScript('groupCount()', function (sheetArray) {
                sheetArray = sheetArray.split(',,');

                for (var i = 0, count = sheetArray.length, vasya = []; count > i; i++) {
                    $.ajax({
                        method: "POST",
                        url: "http://dev.stickerboom.ru:88/sheet/addSheet",
                        data: { stickers: sheetArray[i] }
                    }).done(function(sheetId) {
                        vasya.push(sheetId);
                        csInterface.evalScript('idArray.push('+sheetId+')');
                        if (vasya.length === count) {
                            csInterface.evalScript('starter(idArray)', function () {
                                var months = ["января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"];
                                var curentDate = new Date;
                                curentDate = curentDate.getDate()+ " " + months[curentDate.getMonth()];

                                for (var j = 0, result, path = "", mainPath = "", vasyaArrayLength = vasya.length, resFiledata, id; vasyaArrayLength > j; j++) {
                                    id  = vasya[j];
                                    mainPath = "C:/Users/user/Dropbox/stickerboom/sheets/" + curentDate + "/print/" + id + "/";
                                    path =  mainPath + id + "-preview.jpg";
                                    result = window.cep.fs.readFile(path, 'Base64')
                                    resFiledata = result.data;

                                    if (result.err === 0){
                                        $.ajax({
                                            method: "POST",
                                            url: "http://dev.stickerboom.ru:88/sheet/attachPreviewIllustrator",
                                            data: { sheet_id: id, filedata: resFiledata }
                                        }).done(function( msg ) {
                                            console.log(msg);
                                        });
                                    } else {
                                        console.log(result.err);
                                    }
                                }
                            });
                        }
                    });
                }
            });






        });
    };

    init();
}());

                /*
                $.ajax({
                    method: "POST",
                    url: "http://dev.stickerboom.ru:88/sheet/addSheet",
                    data: { stickers: resStickers }
                }).done(function(sheetId) {
                    alert (sheetId);
                    */
