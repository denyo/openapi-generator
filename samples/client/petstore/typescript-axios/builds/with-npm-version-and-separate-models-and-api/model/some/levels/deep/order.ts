// tslint:disable
/// <reference path="../../../../custom.d.ts" />
/**
 * OpenAPI Petstore
 * This is a sample server Petstore server. For this sample, you can use the api key `special-key` to test the authorization filters.
 *
 * OpenAPI spec version: 1.0.0
 * 
 *
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */



/**
 * An order for a pets from the pet store
 * @export
 * @interface Order
 */
export interface Order {
    /**
     * 
     * @type {number}
     * @memberof Order
     */
    id?: number;
    /**
     * 
     * @type {number}
     * @memberof Order
     */
    petId?: number;
    /**
     * 
     * @type {number}
     * @memberof Order
     */
    quantity?: number;
    /**
     * 
     * @type {Date}
     * @memberof Order
     */
    shipDate?: Date;
    /**
     * Order Status
     * @type {string}
     * @memberof Order
     */
    status?: Order.StatusEnum;
    /**
     * 
     * @type {boolean}
     * @memberof Order
     */
    complete?: boolean;
}

/**
 * @export
 * @namespace Order
 */
export namespace Order {
    /**
     * @export
     * @enum {string}
     */
    export enum StatusEnum {
        Placed = 'placed',
        Approved = 'approved',
        Delivered = 'delivered'
    }
}



