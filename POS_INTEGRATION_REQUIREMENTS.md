# POS Integration Requirements (Complete)

This document lists everything we need from the POS system to integrate inventory and sales with the ecommerce platform. The goal is two-way stock sync so that website orders decrease POS stock and POS sales decrease website stock.

## 1) Access and Security
- API authentication method (API key, OAuth2, JWT, etc.)
- Base URL for production and (if available) sandbox
- Rate limits and expected throughput
- Webhook signing method (HMAC secret, signature header, timestamp)
- Support for idempotency keys on POS APIs (to prevent double deductions)

## 2) Product and Variant Data (Full Export)
We need a complete product export, not minimal fields.

### Required
- POS unique ID (`item_id`)
- `sku` and/or `barcode` (unique per product or variant)
- `name`
- active/inactive status

### Strongly requested (if available)
- category and brand
- unit of measure
- buying cost and selling price
- tax class or tax rate
- currency and decimal precision
- tax inclusive/exclusive pricing flag
- product images (URL or file access)
- description (short/long)
- warranty
- weight
- tags

### Ecommerce configuration fields (if POS can provide)
- can_purchasable (yes/no)
- show_stock_out (enable/disable)
- refundable (yes/no)
- maximum_purchase_quantity
- low_stock_quantity_warning
- shipping type and shipping cost (if POS stores this)
- flash sale / offer flags and offer date range (if POS stores this)

### Variants
If POS has variants (size/color):
- Provide each variant as a separate row with its own `item_id` and `sku`/`barcode`
- Include variant attributes (size, color, etc.)
- Provide parent product relationship if POS supports it

## 3) Inventory (Stock) Data
We use a stock ledger, so we need POS stock as either absolute values or deltas.

### Required
- Endpoint to fetch stock for all items (bulk)
- Endpoint to fetch stock for one item by `sku` or `item_id`
- Endpoint to adjust stock by quantity (delta) OR set absolute stock
- When POS stock is added or adjusted (purchase, manual adjustment), website stock must update to match

### Multi-store support
- `store_id` / `outlet_id` and how stock is tracked per store
- If stock is shared across stores, confirm that model

## 4) POS Sales and Returns Events (Required)
We need real-time or near-real-time POS events to reduce website stock.

### Required events (webhook or polling endpoint)
- `sale.created`
- `sale.canceled` / `sale.voided`
- `return.created` (if returns add stock back)

### Required fields per event
- `event_id` (unique)
- `sale_id` / `order_id`
- timestamp (UTC preferred)
- `store_id` / `outlet_id` (if multi-store)
- line items:
  - `item_id` or `sku`
  - `quantity`
  - `unit_price`
  - `discount` (if any)
  - `tax` (if any)
- payment status (paid/unpaid/refunded)
- Any POS stock adjustment (increase/decrease) must be sent so website stock stays accurate

## 5) Ecommerce -> POS Updates
We will push these events to POS:
- website order paid (reduce stock)
- website order canceled/refunded (restore stock)

Please provide:
- endpoint to create a sale OR stock adjustment
- required fields and validation rules
- expected response format

## 6) Error Handling and Retry
- Webhook retry policy (retries, backoff, failure response expectations)
- How POS expects us to acknowledge a webhook
- How POS handles duplicate events
- POS API error codes and throttling behavior

## 7) Reconciliation and Source of Truth
We need agreement on:
- Which system is the final source of inventory truth
- Conflict resolution rules (which side wins on mismatch)
- Reconciliation frequency (hourly, nightly, etc.)

## 8) Testing and Support
- Sample payloads for `sale.created`, `sale.canceled`, `return.created`
- API documentation (reference + authentication guide)
- Technical contact for integration issues

---

If you can provide the items above, we can finalize the integration design and timeline.
