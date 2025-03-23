<?php

return [
    'age_groups' => [
        'BOARD_BOOKS' => 'Board Books',
        'PICTURE_BOOKS' => 'Picture Books',
        'EARLY_READERS' => 'Early Readers',
        'CHAPTER_BOOKS' => 'Chapter Books',
        'MIDDLE_GRADE' => 'Middle Grade',
        'YOUNG_ADULT' => 'Young Adult',
        'NEW_ADULT' => 'New Adult',
        'ADULT' => 'Adult',
        'UNKNOWN' => 'Unknown',
    ],
    'book_conditions' => [
        'new' => 'New',
        'like_new' => 'Like New',
        'used' => 'Used',
        'rb_petsmart' => 'Reading Buddy Petsmart',
        'rb_purchased' => 'Reading Buddy Purchased',
        'rb_handmade' => 'Reading Buddy Handmade',
    ],
    'book_condition_groups' => [
        'Purchase' => ['new'],
        'In Kind' => ['like_new', 'used'],
        'Reading Buddy' => ['rb_petsmart', 'rb_purchased', 'rb_handmade'],
    ],
    'fulfillment_statuses' => [
        'new' => 'New',
        'preparing' => 'Preparing',
        'pending_shipment' => 'Pending Shipment',
        'shipped' => 'Shipped',
        'cancelled' => 'Cancelled',
    ],
];
