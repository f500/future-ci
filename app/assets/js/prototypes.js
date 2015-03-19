/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

(function ($jquery, $angular) {

    /**
     * @returns {*}
     */
    Array.prototype.first = function () {
        if (this.length) {
            return this[0];
        }

        return undefined;
    };

    /**
     * @returns {*}
     */
    Array.prototype.last = function () {
        if (this.length) {
            return this[this.length - 1];
        }

        return undefined;
    };

    /**
     * @param   {function} callback
     * @returns {array}
     */
    Array.prototype.foreach = function (callback) {
        var length = this.length,
            result,
            i;

        for (i = 0; i < length; i++) {
            result = callback.call(this, this[i], i);
            if (result === false) {
                break;
            }
        }

        return this;
    };

    /**
     * @returns {number}
     */
    Date.prototype.getIsoWeek = function () {
        var date = new Date(date.getTime());
        date.setDate(date.getDate() + 4 - (date.getDay() || 7));

        var time = date.getTime();
        date.setMonth(0);
        date.setDate(1);

        return Math.floor(Math.round((time - date) / 86400000) / 7) + 1;
    };

    /**
     * @returns {number}
     */
    Date.prototype.getIsoYear = function () {
        var date = new Date(date.getTime());
        date.setDate(date.getDate() + 4 - (date.getDay() || 7));
        return date.getFullYear();
    };

    if ($angular.toBoolean === undefined) {
        /**
         * @param   {*} value
         * @returns {boolean}
         */
        $angular.toBoolean = function (value) {
            if (value && value.length !== 0) {
                var v = $angular.lowercase('' + value);
                value = !(v == 'f' || v == '0' || v == 'false' || v == 'no' || v == 'n' || v == '[]');
            } else {
                value = false;
            }

            return value;
        };
    }

}(jQuery, angular));
