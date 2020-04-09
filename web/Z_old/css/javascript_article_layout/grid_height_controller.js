class grid_height_controller {
    constructor(cssClassname, columnsArray) {
        this.items = this.getItems(cssClassname);
        this.columnSettings = this.setColumns(columnsArray);

        window.addEventListener('resize', this.controlResize.bind(this));
        this.run()
    }

    getItems(cssClassname) {
        return document.getElementsByClassName(cssClassname);
    }

    setColumns(array) {
        const columnSettings = {};
        columnSettings.xs = array[0];
        columnSettings.s = array[1];
        columnSettings.m = array[2];
        columnSettings.l = array[3];
        columnSettings.xl = array[4];
        return columnSettings;
    }

    controlResize() {
        for (var i = 0; i < this.items.length; i++) {
            this.items[i].style.height = null;
        }

        this.run();
    }

    run() {
        const itemHeights = this.getItemHeights();
        const columnAmount = this.getColumnAmount();
        this.setHeights(itemHeights, columnAmount);
    }

    getItemHeights() {
        let itemHeights = [];
        for (var i = 0; i < this.items.length; i++) {
            itemHeights[i] = this.items[i].offsetHeight;
        }
        return itemHeights;
    }

    getColumnAmount() {
        const windowWidth = window.innerWidth;
        let columnAmount;
        if (windowWidth > 1280) {
            columnAmount = this.columnSettings.xl;

        } else if (windowWidth > 1000) {
            columnAmount = this.columnSettings.l;

        } else if (windowWidth > 768) {
            columnAmount = this.columnSettings.m;

        } else if (windowWidth > 600) {
            columnAmount = this.columnSettings.s;

        } else {
            columnAmount = this.columnSettings.xs;
        }
        return columnAmount;
    }

    setHeights(itemHeights, columnAmount) {
        let specialCounter = 0;
        let rowReset = 1;
        let height;

        for (let i = 0; i < itemHeights.length; i++) {
            if (rowReset === 1) {
                rowReset = 0;
                height = itemHeights[i];

                for (var ii = 1; ii < columnAmount; ii++) {
                    if (height < itemHeights[i+ii]) {
                        height = itemHeights[i+ii];
                    }
                }
            }
            // console.log(this.items[i]);
            this.items[i].style.height = (height+30) + 'px';

            if (++specialCounter >= columnAmount) {
                rowReset = 1;
                specialCounter = 0;
            }
        }
    }
}
