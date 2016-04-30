function createBorders() {
    var docRef = app.activeDocument;
    var border = +prompt("Введите размер отступа:", "2");
    border = border * 2.834645669;
        
    var arrSelectedItems = docRef.selection;
        
    for (var i = 0, obj, len = arrSelectedItems.length, topLeft, topRight, bottomLeft, bottomRight, lines; len > i; i++) {

        obj = arrSelectedItems[i].parent.typename === 'Layer' ?  arrSelectedItems[i] : arrSelectedItems[i].parent;
                        
            topLeft = [obj.left - border, obj.top + border];
            topRight = [obj.left + obj.width + border, obj.top + border];
            bottomLeft = [obj.left - border, obj.top - obj.height - border];
            bottomRight = [obj.left + obj.width + border, obj.top - obj.height - border];
            
            lines = new Array (4);
            lines[0] = new Array (topLeft, topRight); // topLine
            lines[1] = new Array (topRight, bottomRight); // rightLine
            lines[2]= new Array (bottomLeft, bottomRight); // bottomLine
            lines[3] = new Array (topLeft, bottomLeft); // leftLine
            
            for (var j = 0, borders = docRef.groupItems.add(); lines.length > j; j++) {
                newPath = docRef.pathItems.add();
                newPath.setEntirePath(lines[j]);
                newPath.filled = false;
                newPath.stroked = true;
                newPath.strokeColor = getCutContourColor();
                newPath.move(borders, ElementPlacement.INSIDE);
                }
            
            borders.name = "borders";   
                
            if (obj.typename === 'GroupItem') {
                borders.move(obj, ElementPlacement.PLACEATEND);
            } else {
                var mainGroup = docRef.groupItems.add();
                obj.move(mainGroup, ElementPlacement.INSIDE);
                borders.move(mainGroup, ElementPlacement.PLACEATEND);
            }
        } 
    $.gc();
    }

function getCutContourColor() {
    var newSwatchColor;

    try {
        if (activeDocument.swatches.getByName("CutContour") !== undefined) {
            newSwatchColor = activeDocument.swatches.getByName("CutContour");
            newSwatchColor.color.spot.color.cyan = 100;
            newSwatchColor.color.spot.color.magenta = 0;
            newSwatchColor.color.spot.color.yellow = 0;
            newSwatchColor.color.spot.color.black = 0;

            return newSwatchColor.color;
        }
    } catch(e) {
        newSwatchColor = activeDocument.swatches.add(); 
        var newSpotColor = new SpotColor();
        var newColor = new CMYKColor();
        var newSpot = activeDocument.spots.add();

        newColor.cyan = 100;
        newColor.magenta = 0;
        newColor.yellow = 0;
        newColor.black = 0;

        newSpot.color = newColor;
        newSpot.colorType = ColorModel.SPOT;
        newSpot.name = "CutContour";

        newSpotColor.spot = newSpot;
        newSwatchColor.color = newSpotColor;
        newSwatchColor.name = "CutContour";

        return newSwatchColor.color;
        }
    }