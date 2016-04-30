function placing() {
    if (app.documents.length > 0 && app.activeDocument.pageItems.length === 0) {
        app.executeMenuCommand("AI Place");
        } else {
            app.documents.add();
            app.executeMenuCommand("AI Place");
            }
        }