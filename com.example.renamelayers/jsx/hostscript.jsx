#target Illustrator
#include "./functions/placing.jsx"
#include "./functions/creatingBorders.jsx"
#include "./functions/transporter.jsx"

function unmountClippedGroup (group) {
    
    function sortByKey(array, key) {
        return array.sort (function (a, b) {
            var x = a[key]; 
            var y = b[key];
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            });
        }
    
    var arrayForSorting = [];
    for (var i = 0, len = group.groupItems.length; len > i; i++) {
        if (group.groupItems[i].typename === 'GroupItem') arrayForSorting.push(group.groupItems[i]);
        }
    sortByKey(arrayForSorting, 'width');
    if (arrayForSorting.length > 0) {
        outer1: for (var k = arrayForSorting.length-1, len = arrayForSorting.length; len > 0; k--) {
            if (arrayForSorting[k].pathItems[0].clipping === true && (arrayForSorting[k].pageItems[1].typename === 'CompoundPathItem' || arrayForSorting[k].pageItems[1].typename === 'PathItem')) {
                arrayForSorting[k].pathItems[0].remove(); 
                arrayForSorting[k].pageItems[0].move(group, ElementPlacement.INSIDE);
                break outer1;
                }
            }
        }
    }


function cloneAndResize(selectedItem) {
    
    app.executeMenuCommand("deselectall");
    var docRef = app.activeDocument;
    var arrNameParts = selectedItem.file.name.indexOf("%20pcs") !== -1 ? selectedItem.file.name.replace("%20pcs.eps", "").split("-") : "";
    selectedItem.embed();
    var currentGroup = docRef.selection[0];
    unmountClippedGroup(currentGroup);
    
    // Получение ширины
    if (Object.prototype.toString.call(arrNameParts) === '[object Array]') {
        if (arrNameParts[1].indexOf("x") !== -1) {
            // Ресайз
            var width = arrNameParts[1].split("x").shift();
            var sizeCoeff = currentGroup.height / currentGroup.width;
            currentGroup.width = (2.834645669 * width) * 10;
            currentGroup.height = currentGroup.width * sizeCoeff;
            var clonedCurrentGroup;
            // Копирование l
            while (arrNameParts[2] > 1) {
                clonedCurrentGroup = currentGroup.duplicate();
                clonedCurrentGroup.name = arrNameParts[0] + "-" + arrNameParts[1] + "-1";
                arrNameParts[2]--;
                }
            currentGroup.name = arrNameParts[0] + "-" + arrNameParts[1] + "-" + arrNameParts[2];
            } else { // В случае, если в ресайзе нет необходимости
                while (arrNameParts[1] > 1) {
                    clonedCurrentGroup = currentGroup.duplicate();
                    clonedCurrentGroup.name = arrNameParts[0] + "-1";
                    arrNameParts[1]--;
                    }
                currentGroup.name = arrNameParts[0] + "-" + arrNameParts[1];
                }
        } else { 
            currentGroup.name = arrNameParts; // Если имя файла не соотвествовало стандарту
            }
      }
    
function startCloneandResizing() {
    var docRef = app.activeDocument;
    while (docRef.placedItems.length > 0) {
        cloneAndResize(app.activeDocument.placedItems[0]);
        }
    $.gc();
    app.executeMenuCommand("selectall");
    }