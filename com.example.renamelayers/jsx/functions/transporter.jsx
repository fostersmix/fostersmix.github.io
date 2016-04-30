var idArray = [];

function starter(array) {
   for (var i = 0, len = array.length; len > i; i++) {
       transporter (array[i]);
       }
   idArray = [];
   $.gc();
    }

function groupCount() {
    for (var i = 0, len = app.activeDocument.groupItems.length, stickers = []; len > i; i++) {
        if (app.activeDocument.groupItems[i].parent.typename === 'Layer') {
            stickers.push(returnStickersNames (app.activeDocument.groupItems[i]));
            }
        }
    return stickers;
    }

function returnStickersNames (group) {
    for (var i = 0, resStickers = "", len = group.groupItems.length; len > i; i++) {
        if (group.groupItems[i].name !== "" && group.groupItems[i].name !== 'borders') {
            resStickers += group.groupItems[i].name + ",";
            }
        }
    return resStickers;
    }

function transporter(id) {
    var currentDocument = app.activeDocument;
    var newDocument;
    for (var i = 0, len = app.activeDocument.groupItems.length, mainGroups = []; len > i; i++) {
        if (app.activeDocument.groupItems[i].parent.typename === 'Layer') {
            mainGroups.push(app.activeDocument.groupItems[i]);
            }
        }
    if (mainGroups.length === 1) {
        currentDocument.artboards[0].artboardRect = mainGroups[0].visibleBounds;
        documentSaving(currentDocument, id);
        } else {
            newDocument = app.documents.add();
            mainGroups[0].move(newDocument, ElementPlacement.INSIDE);
            newDocument.artboards[0].artboardRect = mainGroups[0].visibleBounds;
            documentSaving(newDocument, id);
            }
        }

function documentSaving(doc, id) {
    var months = ["января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"];
    var curentDate = new Date;
    curentDate =  curentDate.getDate() + " " + months[curentDate.getMonth()];
        
    app.executeMenuCommand("selectall");
    doc.layers[0].name = id;
    var path = 'C:/Users/user/Dropbox/stickerboom/sheets/' + curentDate + '/print/' + id + '/';
    newFolder = new Folder(path);
    if (!newFolder.exists) newFolder.create();

    exportJPEG(doc, path + id + ' preview');
    exportFileAsEPS(doc, path + id);
    doc.close(SaveOptions.DONOTSAVECHANGES);
    }

function exportFileAsEPS (saveDoc, destFile) {
    var newFile = new File(destFile);
    var saveOpts = new EPSSaveOptions();

    saveOpts.cmykPostScript = true;
    saveOpts.compatibility = Compatibility.ILLUSTRATOR17;
    saveOpts.includeDocumentThumbnails = false;
    saveOpts.preview = EPSPreview.None;
    saveOpts.flattenOuput = OutputFlattening.PRESERVEPATHS;    
        
    saveDoc.saveAs (newFile, saveOpts);
}

function exportJPEG (saveDoc, destFile) {
    var fileSpec = new File(destFile);
    var exportOptions = new ExportOptionsJPEG;
    var type = ExportType.JPEG;
    
    exportOptions.antiAliasing = false;
    exportOptions.qualitySetting = 30;
    saveDoc.exportFile(fileSpec, type, exportOptions);
    }