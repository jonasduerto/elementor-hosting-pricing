/**
 * Elementor Hosting Pricing Widget JS
 * Handles the annual/monthly toggle functionality for pricing tables
 * 
 * @author Jonas D
 */

jQuery(function($) {
    'use strict';

    // Clase principal para manejar el widget de precios (por instancia)
    class HostingPricingWidget {
        constructor(root) {
            this.$root = $(root);
            this.selectors = {
                toggleContainer: '.hosting-billing-toggle',
                toggleCheckbox: '.hosting-billing-toggle-input',
                option: '.hosting-billing-option',
                priceContainer: '.hosting-plan-pricing',
                monthlyPrice: '.monthly-price',
                annualPrice: '.annual-price',
                payToday: '.hosting-pay-today',
                annualSavings: '.hosting-annual-savings',
                billedAmount: '.hosting-billed-amount',
                savingsAmount: '.hosting-savings-amount',
                billingCycle: '.hosting-billing-cycle'
            };

            // Formato de moneda configurable desde el widget
            const decimals = parseInt(this.$root.data('decimals'), 10);
            this.format = {
                currency: this.$root.data('currency') !== undefined ? String(this.$root.data('currency')) : '$',
                after: 'after' === this.$root.data('currency-position'),
                decimals: isNaN(decimals) ? 2 : Math.max(0, Math.min(4, decimals)),
                period: this.$root.data('period') !== undefined ? String(this.$root.data('period')) : '/mo'
            };

            // Clave de almacenamiento para recordar la elección del visitante
            this.storageKey = 'hostingPricing:billing';
            this.remember = 'no' !== this.$root.attr('data-remember');

            this.init();
        }

        amount(value) {
            return (parseFloat(value) || 0).toFixed(this.format.decimals);
        }

        /**
         * Lee la preferencia guardada. Devuelve null si no existe o no hay acceso.
         */
        readStoredBilling() {
            if (!this.remember) {
                return null;
            }

            try {
                const stored = window.localStorage.getItem(this.storageKey);
                return 'annual' === stored || 'monthly' === stored ? stored : null;
            } catch (e) {
                return null;
            }
        }

        storeBilling(isAnnual) {
            if (!this.remember) {
                return;
            }

            try {
                window.localStorage.setItem(this.storageKey, isAnnual ? 'annual' : 'monthly');
            } catch (e) {
                // El almacenamiento puede estar bloqueado (modo privado): se ignora.
            }
        }

        init() {
            this.cacheElements();
            this.bindEvents();
            // Estado inicial: preferencia guardada > data del contenedor > checkbox
            const stored = this.readStoredBilling();
            const dataBilling = stored || this.$toggleContainer.attr('data-billing');
            const isAnnual = dataBilling ? dataBilling === 'annual' : this.$toggleCheckbox.is(':checked');
            this.$toggleContainer.attr('data-billing', isAnnual ? 'annual' : 'monthly');
            this.updatePrices(!!isAnnual);
            this.syncToggleUI(!!isAnnual);
        }

        cacheElements() {
            this.$toggleContainer = this.$root.find(this.selectors.toggleContainer);
            this.$toggleCheckbox = this.$root.find(this.selectors.toggleCheckbox);
            this.$options = this.$root.find(this.selectors.option);
            this.$priceContainers = this.$root.find(this.selectors.priceContainer);
            this.$monthlyPrices = this.$root.find(this.selectors.monthlyPrice);
            this.$annualPrices = this.$root.find(this.selectors.annualPrice);
            this.$payToday = this.$root.find(this.selectors.payToday);
            this.$annualSavings = this.$root.find(this.selectors.annualSavings);
        }

        bindEvents() {
            // Click en las opciones de facturación
            this.$options.on('click', (e) => this.onOptionClick(e));

            // Cambio del checkbox (accesibilidad)
            this.$toggleCheckbox.on('change', (e) => {
                const isAnnual = this.$toggleCheckbox.is(':checked');
                this.$toggleContainer.attr('data-billing', isAnnual ? 'annual' : 'monthly');
                this.updatePrices(isAnnual);
                this.syncToggleUI(isAnnual);
            });

            // Teclado en opciones: Enter/Espacio activan, flechas alternan ciclo
            this.$options.attr('tabindex', 0).on('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    $(e.currentTarget).trigger('click');
                    return;
                }

                if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
                    e.preventDefault();
                    const target = e.key === 'ArrowRight' ? 'annual' : 'monthly';
                    this.$options.filter(`[data-billing="${target}"]`).trigger('click').trigger('focus');
                }
            });
        }

        syncToggleUI(isAnnual) {
            // Actualizar checkbox y ARIA
            this.$toggleCheckbox.prop('checked', !!isAnnual);
            this.$options.attr('aria-pressed', 'false');
            this.$options.removeClass('active');
            const $current = this.$options.filter(`[data-billing="${isAnnual ? 'annual' : 'monthly'}"]`);
            $current.addClass('active').attr('aria-pressed', 'true');
        }

        onOptionClick(e) {
            e.preventDefault();
            const $clickedOption = $(e.currentTarget);
            const isAnnual = $clickedOption.data('billing') === 'annual';

            // Actualizar el data-billing del contenedor
            this.$toggleContainer.attr('data-billing', isAnnual ? 'annual' : 'monthly');

            // Sincronizar UI (incluye checkbox y clases)
            this.syncToggleUI(isAnnual);

            // Actualizar precios
            this.updatePrices(isAnnual);
        }

        updatePrices(isAnnual) {
            // Actualizar visualización de precios
            this.$monthlyPrices.toggleClass('active', !isAnnual);
            this.$annualPrices.toggleClass('active', isAnnual);

            // Actualizar texto de facturación y ahorros para cada plan
            this.$root.find('.hosting-pricing-plan').each((index, element) => {
                const $plan = $(element);
                const monthlyPrice = parseFloat($plan.data('monthly-price')) || 0;
                const annualPrice = parseFloat($plan.data('annual-price')) || 0;

                if (isAnnual) {
                    // Modo anual: mostrar precio anual por mes
                    const annualTotal = this.amount(annualPrice * 12);
                    const savings = this.amount(monthlyPrice * 12 - annualPrice * 12);

                    // Actualizar el precio mostrado (precio mensual del plan anual)
                    $plan.find('.hosting-price-amount').text(this.amount(annualPrice));
                    $plan.find('.hosting-billing-cycle').text(this.format.period);

                    // Actualizar el texto de facturación - más compacto
                    $plan.find('.hosting-billed-amount').text(annualTotal);
                    $plan.find('.hosting-savings-amount').text(savings);

                    // Mostrar información de facturación anual
                    $plan.find('.hosting-pay-today').show();
                    $plan.find('.hosting-annual-savings').show();
                } else {
                    // Modo mensual: mostrar precio mensual
                    $plan.find('.hosting-price-amount').text(this.amount(monthlyPrice));
                    $plan.find('.hosting-billing-cycle').text(this.format.period);

                    // Ocultar información de facturación anual
                    $plan.find('.hosting-pay-today').hide();
                    $plan.find('.hosting-annual-savings').hide();
                }
            });

            // Guardar la elección y anunciarla a lectores de pantalla
            this.storeBilling(isAnnual);
            this.$root.find('.hosting-billing-status').text(
                isAnnual ? this.$root.data('annual-announce') || 'Annual billing selected'
                         : this.$root.data('monthly-announce') || 'Monthly billing selected'
            );

            // Disparar evento personalizado para cualquier funcionalidad adicional
            this.$root.trigger('hostingPricing:updated', { isAnnual });
            $(document).trigger('hostingPricing:updated', { isAnnual });
        }
    }

    // Inicializar para cada instancia del widget presente en la página
    $('.elementor-widget-hosting_pricing .hosting-pricing-widget').each(function() {
        if (!$(this).data('hpw-initialized')) {
            $(this).data('hpw-initialized', true);
            new HostingPricingWidget(this);
        }
    });

    // Integración con el editor de Elementor
    if (window.elementor && window.elementor.hooks) {
        elementor.hooks.addAction('panel/open_editor/widget', function(panel, model) {
            if (model && model.attributes && model.attributes.widgetType === 'hosting_pricing') {
                setTimeout(() => {
                    $('.elementor-widget-hosting_pricing .hosting-pricing-widget').each(function() {
                        // Evitar inicializar duplicado añadiendo marca
                        if (!$(this).data('hpw-initialized')) {
                            $(this).data('hpw-initialized', true);
                            new HostingPricingWidget(this);
                        }
                    });
                }, 300);
            }
        });
    }
});
