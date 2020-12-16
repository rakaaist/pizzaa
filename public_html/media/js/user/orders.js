'use strict';

const endpoints = {
    get: 'api/users/order/get',
    create: 'api/users/order/create'
};

/**
 * This defines how JS code selects elements by ID
 */
const selectors = {
    table: 'my-order-table'
}

/**
 * Executes API request
 * @param {type} url Endpoint URL
 * @param {type} formData instance of FormData
 * @param {type} success Success callback
 * @param {type} fail Fail callback
 * @returns {undefined}
 */
function api(url, formData, success, fail) {
    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => response.json())
        .then(obj => {
            if (obj.status === 'success') {
                success(obj.data);
            } else {
                fail(obj.errors);
            }
        })
        .catch(e => {
            if (e.toString() == 'SyntaxError: Unexpected token < in JSON at position 0') {
                fail(['Problem is with your API controller, did not return JSON! Check Chrome->Network->XHR->Response']);
            } else {
                fail([e.toString()]);
            }
        });
}

/**
 * Table-related functionality
 */
const table = {
    getElement: function () {
        return document.getElementById(selectors.table);
    },
    init: function () {
        if (this.getElement()) {
            this.data.load();

            Object.keys(this.buttons).forEach(buttonId => {
                let success = table.buttons[buttonId].init();
                console.log('Setting up button listeners "' + buttonId + '": ' + (success ? 'PASS' : 'FAIL'));
            });

            return true;
        }

        return false;
    },
    /**
     * Data-Related functionality
     */
    data: {
        /**
         * Loads data and populates table from API
         * @returns {undefined}
         */
        load: function () {
            console.log('Table: Calling API to get data...');
            api(endpoints.get, null, this.success, this.fail);
        },
        success: function (data) {
            Object.keys(data).forEach(i => {
                table.row.append(data[i]);
            });
        },
        fail: function (errors) {
            console.log(errors);
        }
    },
    /**
     * Operations with items
     */
    row: {
        /**
         * Builds item element from data
         *
         * @param {Object} data
         * @returns {Element}
         */
        build: function (data) {
            const row = document.createElement('tr');

            if (data.id == null) {
                throw Error('JS can`t build the item, because API data doesn`t contain its ID. Check API controller!');
            }

            row.setAttribute('data-id', data.id);
            row.className = 'data-item';

            Object.keys(data).forEach(data_id => {
                        let span = document.createElement('span');
                        span.innerHTML = data[data_id];
                        span.className = data_id;
                        row.append(span);
            });

            return row;
        },
        /**
         * Appends row to table from data
         *
         * @param {Object} data
         */
        append: function (data) {
            console.log('Table: Creating row in table container from ', data);
            table.getElement().append(this.build(data));
        }
    },
};

/**
 * Core page functionality
 */
const app = {
    init: function () {
        // Initialize all forms
        Object.keys(forms).forEach(formId => {
            let success = forms[formId].init();
            console.log('Initializing form "' + formId + '": ' + (success ? 'SUCCESS' : 'FAIL'));
        });

        console.log('Initializing grid...');
        let success = grid.init();
        console.log('Grid: Initialization: ' + (success ? 'PASS' : 'FAIL'));
    }
};

// Launch App
app.init();