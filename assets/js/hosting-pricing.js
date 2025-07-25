/**
 * Elementor Hosting Pricing Widget JS
 * Handles the annual/monthly toggle functionality for pricing tables
 * 
 * @author Team Tipihost
 * @author URI: https://tipihost.com
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Clase principal para manejar el widget de precios
    class HostingPricingWidget {
        constructor() {
            this.selectors = {
                widget: '.hosting-pricing-widget',
                toggleContainer: '.hosting-billing-toggle',
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
            
            this.init();
        }
        
        init() {
            this.cacheElements();
            this.bindEvents();
            this.updatePrices(false); // Inicializar con precios mensuales
        }
        
        cacheElements() {
            this.$widget = $(this.selectors.widget);
            this.$toggleContainer = this.$widget.find(this.selectors.toggleContainer);
            this.$options = this.$widget.find(this.selectors.option);
            this.$priceContainers = this.$widget.find(this.selectors.priceContainer);
            this.$monthlyPrices = this.$widget.find(this.selectors.monthlyPrice);
            this.$annualPrices = this.$widget.find(this.selectors.annualPrice);
            this.$payToday = this.$widget.find(this.selectors.payToday);
            this.$annualSavings = this.$widget.find(this.selectors.annualSavings);
        }
        
        bindEvents() {
            // Click en las opciones de facturación
            this.$options.on('click', (e) => this.onOptionClick(e));
            
            // Manejar redimensionamiento para ajustes responsivos
            $(window).on('resize', this.handleResize.bind(this));
        }
        
        onOptionClick(e) {
            e.preventDefault();
            const $clickedOption = $(e.currentTarget);
            const isAnnual = $clickedOption.data('billing') === 'annual';
            
            // Actualizar la clase activa y el data-billing del contenedor
            this.$options.removeClass('active');
            $clickedOption.addClass('active');
            this.$toggleContainer.attr('data-billing', isAnnual ? 'annual' : 'monthly');
            
            // Actualizar precios
            this.updatePrices(isAnnual);
        }
        
        handleResize() {
            // Puedes agregar lógica de redimensionamiento aquí si es necesario
        }
        
        updatePrices(isAnnual) {
            // Actualizar clases activas en las opciones
            this.$options.removeClass('active');
            this.$options.filter(`[data-billing="${isAnnual ? 'annual' : 'monthly'}"]`).addClass('active');
            
            // Actualizar visualización de precios
            this.$monthlyPrices.toggleClass('active', !isAnnual);
            this.$annualPrices.toggleClass('active', isAnnual);
            
            // Actualizar texto de facturación y ahorros para cada plan
            this.$widget.find('.hosting-pricing-plan').each(function() {
                const $plan = $(this);
                const monthlyPrice = parseFloat($plan.data('monthly-price')) || 0;
                const annualPrice = parseFloat($plan.data('annual-price')) || 0;
                
                if (isAnnual) {
                    // Modo anual: mostrar precio anual por mes
                    const annualTotal = (annualPrice * 12).toFixed(2);
                    const savings = (monthlyPrice * 12 - annualPrice * 12).toFixed(2);
                    
                    // Actualizar el precio mostrado (precio mensual del plan anual)
                    $plan.find('.hosting-price-amount').text(annualPrice.toFixed(2));
                    $plan.find('.hosting-billing-cycle').text('/mo');
                    
                    // Actualizar el texto de facturación - más compacto
                    $plan.find('.hosting-billed-amount').text(annualTotal);
                    $plan.find('.hosting-savings-amount').text(savings);
                    
                    // Mostrar información de facturación anual
                    $plan.find('.hosting-pay-today').show();
                    $plan.find('.hosting-annual-savings').show();
                } else {
                    // Modo mensual: mostrar precio mensual
                    $plan.find('.hosting-price-amount').text(monthlyPrice.toFixed(2));
                    $plan.find('.hosting-billing-cycle').text('/mo');
                    
                    // Ocultar información de facturación anual
                    $plan.find('.hosting-pay-today').hide();
                    $plan.find('.hosting-annual-savings').hide();
                }
            });
            
            // Disparar evento personalizado para cualquier funcionalidad adicional
            $(document).trigger('hostingPricing:updated', { isAnnual });
        }
    }
    
    // Inicializar el widget cuando el DOM esté listo
    $(document).ready(() => {
        // Initialize for each widget instance
        $('.elementor-widget-hosting_pricing').each(function() {
            new HostingPricingWidget();
        });
        
        // For Elementor editor preview
        if (window.elementor) {
            elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
                if (model.attributes.widgetType === 'hosting_pricing') {
                    setTimeout(() => {
                        $('.elementor-widget-hosting_pricing').each(function() {
                            new HostingPricingWidget();
                        });
                    }, 300);
                }
            });
        }
    });
});
