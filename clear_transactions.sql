TRUNCATE purchase_detail;
TRUNCATE purchase_head;
TRUNCATE damage_head;
TRUNCATE damage_detail;
TRUNCATE return_head;
TRUNCATE retrun_detail;
UPDATE product_branch_inventory SET inventory = 0, min_inv = 1, max_inv = 1;