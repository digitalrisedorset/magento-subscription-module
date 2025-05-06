## 📦 `Drd_Subscribe` — Magento 2 Subscription Module

A lightweight, professional-grade Magento 2 module to enable **subscription-based products** with:

* **Subscribe & Save** pricing
* Custom recurrence plans (weekly, monthly, etc.)
* Dynamic subscription management
* Seamless integration with cart, orders, and My Account

---

## 🚀 Features

* Add subscription plans with fixed recurrence and discounts
* Toggle “One-time Purchase” vs “Subscribe & Save” on product pages
* Supports simple and configurable products (via parent config)
* Automatically creates subscriptions on successful order
* View and manage active subscriptions in **My Account**
* “Skip Next Order” functionality
* Custom ViewModels and LESS for polished integration

---

## 🧱 Architecture Highlights

* Decoupled from Magento's native quote totals and cart logic
* Discounts applied via plugin on `Cart::afterAddProduct()`
* Subscription plans loaded from `subscription_plans.xml`
* Clean frontend templates with ViewModels
* Fully theme-compatible (Luma-compliant)

---

## 🔧 Installation

> Coming soon as a composer package
> Or clone directly into `app/code/Drd/Subscribe` and run:

```bash
bin/magento module:enable Drd_Subscribe
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento cache:flush
```

---

## ✍️ Configuration

### 1. Define Subscription Plans

Add plans to:

```
etc/subscription_plans.xml
```

Example:

```xml
<plan id="monthly_10">
    <label>Monthly - 10% Off</label>
    <frequency>1m</frequency>
    <discount>10</discount>
</plan>
```

### 2. Assign Plans to Products

Use the `subscription_plan_ids` multiselect attribute
Stored on **configurable parent** for simplicity and reuse

---

## 👤 Customer Experience

### On PDP:

* Choose between one-time and subscription
* See available plans and frequencies
* Subscribe with discounted pricing

### In Cart:

* Discount is clearly displayed
* Price adjusted dynamically via plugin
* Plan metadata stored on quote item

### In Account:

* “My Subscriptions” tab shows active subscriptions
* View Original Order / View Subscription Orders
* Skip Next Order button

---

## 🥪 Developer Notes

* ViewModels used throughout for clean templates
* LESS is modular: `_subscriptions.less`, `_module.less`, etc.
* All custom logic scoped to module — no core overrides

---

## 🗕️ Roadmap Ideas

* Subscription pause/resume
* Scheduled recurring orders
* Admin panel to manage active subscriptions
* Email reminders for next order date

---

## ⚠️ Known Issues

### Wishlist Integration Limitation

This module customizes the cart item renderer blocks (`default`, `simple`, and `configurable`) to inject subscription details. 
Due to how Magento processes layout XML and module load order, the `Move to Wishlist` button (injected by `Magento_Wishlist` into `checkout.cart.item.renderers.default.actions`) **may not render correctly**.
At present, full compatibility with Magento's Wishlist module **is not guaranteed**. 
The cart item actions block exists as expected, but **Wishlist's layout injection may be skipped** if the renderer blocks are not available when it runs.
We recommend testing the integration thoroughly if Wishlist is a required feature.

---

## 📅 License

This module is proprietary but may be open-sourced in future.
Built by \[Your Name / Your Company].

