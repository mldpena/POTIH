TRUNCATE purchase_detail;
TRUNCATE purchase_head;
TRUNCATE damage_head;
TRUNCATE damage_detail;
TRUNCATE return_head;
TRUNCATE return_detail;
TRUNCATE purchase_receive_detail;
TRUNCATE purchase_receive_head;
UPDATE product_branch_inventory SET inventory = 0, min_inv = 1, max_inv = 1;