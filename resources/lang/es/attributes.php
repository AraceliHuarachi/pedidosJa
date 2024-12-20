<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the translated names of form fields.
    | These translations replace the attribute placeholders with more reader-friendly
    | names, making the validation messages clearer and easier to understand.
    |
    */
    'day_date' => 'fecha del día',
    'orders' => 'pedidos',
    'Order_Nro' => 'número de orden', // For nested field names
    'orders.*.date' => 'fecha del pedido',
    'orders.*.products' => 'productos',
    'orders.*.products.*.order_product_id' => 'ID del producto',
    'quantity' => 'cantidad de productos',
];
