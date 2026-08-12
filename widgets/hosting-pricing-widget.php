<?php
/**
 * Elementor Hosting Pricing Widget
 *
 * Generic info (editor help):
 * - Add multiple plans in the "Pricing Plans" repeater. Each plan supports name, subtitle, prices, and CTA.
 * - Monthly Price vs Annual Price: Annual is the discounted per-month amount when billed yearly.
 * - Featured Plan highlights one card visually.
 * - Button URL: Set target and nofollow under the link control options.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Elementor_Hosting_Pricing_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'hosting_pricing';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__( 'Hosting Pricing', 'elementor-hosting-pricing' );
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-price-table';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return [ 'general' ];
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return [ 'hosting', 'pricing', 'plans', 'pricing table' ];
    }

    public function get_script_depends() {
        return [ 'hosting-pricing-js' ];
    }

    public function get_style_depends() {
        return [ 'hosting-pricing-css' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Register content controls.
     */
    protected function register_content_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__('Content', 'elementor-hosting-pricing'),
            ]
        );


        $this->add_control(
            'currency_symbol',
            [
                'label' => esc_html__('Currency Symbol', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '$',
                'description' => esc_html__('Symbol shown next to every price (e.g. $, €, £, MXN).', 'elementor-hosting-pricing'),
            ]
        );

        $this->add_control(
            'currency_position',
            [
                'label' => esc_html__('Currency Position', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'before',
                'options' => [
                    'before' => esc_html__('Before price ($9.99)', 'elementor-hosting-pricing'),
                    'after' => esc_html__('After price (9.99€)', 'elementor-hosting-pricing'),
                ],
            ]
        );

        $this->add_control(
            'price_decimals',
            [
                'label' => esc_html__('Decimals', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 0,
                'max' => 4,
                'description' => esc_html__('Number of decimals used to display prices.', 'elementor-hosting-pricing'),
            ]
        );

        $this->add_control(
            'price_prefix_text',
            [
                'label' => esc_html__('Price Prefix', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('From', 'elementor-hosting-pricing'),
                'description' => esc_html__('Small text above the price. Leave empty to hide it.', 'elementor-hosting-pricing'),
            ]
        );

        $this->add_control(
            'period_label',
            [
                'label' => esc_html__('Period Label', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '/mo',
                'description' => esc_html__('Suffix after the price, e.g. /mo, /month, /mes.', 'elementor-hosting-pricing'),
            ]
        );

        $this->add_control(
            'show_toggle',
            [
                'label' => esc_html__('Show Billing Toggle', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'default_billing',
            [
                'label' => esc_html__('Default Billing Cycle', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'monthly',
                'options' => [
                    'monthly' => esc_html__('Monthly', 'elementor-hosting-pricing'),
                    'annual' => esc_html__('Annual', 'elementor-hosting-pricing'),
                ],
            ]
        );

        $this->add_control(
            'monthly_label',
            [
                'label' => esc_html__('Monthly Label', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Monthly', 'elementor-hosting-pricing'),
                'condition' => [ 'show_toggle' => 'yes' ],
            ]
        );

        $this->add_control(
            'annual_label',
            [
                'label' => esc_html__('Annual Label', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Annual (Save 20%)', 'elementor-hosting-pricing'),
                'condition' => [ 'show_toggle' => 'yes' ],
            ]
        );

        $this->add_control(
            'badge_text',
            [
                'label' => esc_html__('Featured Badge Text', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Popular', 'elementor-hosting-pricing'),
                'description' => esc_html__('Ribbon label shown on the featured plan.', 'elementor-hosting-pricing'),
            ]
        );

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'plan_name',
            [
                'label' => esc_html__('Plan Name', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Website', 'elementor-hosting-pricing'),
                'description' => esc_html__('Display name of the plan (e.g., Basic, Pro, Business).', 'elementor-hosting-pricing'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'subtitle',
            [
                'label' => esc_html__('Subtitle', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => esc_html__('Web Presence: Ideal for those seeking a basic and effective web presence. Start with our standard plan designed for moderate traffic.', 'elementor-hosting-pricing'),
                'description' => esc_html__('Optional short description below the price. You can use limited HTML for emphasis.', 'elementor-hosting-pricing'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'monthly_price',
            [
                'label' => esc_html__('Monthly Price', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '24.99',
                'step' => '0.01',
                'description' => esc_html__('The per-month price when billed monthly.', 'elementor-hosting-pricing'),
            ]
        );

        $repeater->add_control(
            'annual_price',
            [
                'label' => esc_html__('Annual Price', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '19.99',
                'step' => '0.01',
                'description' => esc_html__('Discounted per-month price when billed yearly. The UI multiplies this by 12 for the yearly total.', 'elementor-hosting-pricing'),
            ]
        );



        $repeater->add_control(
            'features',
            [
                'label' => esc_html__('Features', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 6,
                'default' => '',
                'description' => esc_html__('One feature per line. Rendered as a checked list. Leave empty to hide.', 'elementor-hosting-pricing'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Order Now', 'elementor-hosting-pricing'),
                'description' => esc_html__('Call-to-action label for the plan button.', 'elementor-hosting-pricing'),
            ]
        );

        $repeater->add_control(
            'button_url',
            [
                'label' => esc_html__('Button URL', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementor-hosting-pricing'),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'description' => esc_html__('Destination link for the CTA button. Use the gear icon to set target and nofollow.', 'elementor-hosting-pricing'),
            ]
        );

        $repeater->add_control(
            'featured',
            [
                'label' => esc_html__('Featured Plan?', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'elementor-hosting-pricing'),
                'label_off' => esc_html__('No', 'elementor-hosting-pricing'),
                'return_value' => 'yes',
                'default' => '',
                'description' => esc_html__('Highlights this plan visually as recommended/popular.', 'elementor-hosting-pricing'),
            ]
        );

        $repeater->add_control(
            'bottom_text',
            [
                'label' => esc_html__('Bottom Text', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Optimize your online presence.', 'elementor-hosting-pricing'),
                'description' => esc_html__('This text appears below the button', 'elementor-hosting-pricing'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'plans',
            [
                'label' => esc_html__('Pricing Plans', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'plan_name' => esc_html__('Website', 'elementor-hosting-pricing'),
                        'subtitle' => '<b>' . esc_html__('Web Presence', 'elementor-hosting-pricing') . '</b>: ' . esc_html__('Ideal for those seeking a basic and effective web presence. Start with our standard plan designed for moderate traffic.', 'elementor-hosting-pricing'),
                        'monthly_price' => '24.99',
                        'annual_price' => '19.99',

                        'button_text' => esc_html__('Order Now', 'elementor-hosting-pricing'),
                        'bottom_text' => esc_html__('Optimize your online presence.', 'elementor-hosting-pricing'),
                    ],
                    [
                        'plan_name' => esc_html__('Online Store', 'elementor-hosting-pricing'),
                        'subtitle' => '<b>' . esc_html__('E-commerce Launch', 'elementor-hosting-pricing') . '</b>: ' . esc_html__('The perfect option to kickstart your online store. Get specialized hosting with essential features to start selling on the Internet.', 'elementor-hosting-pricing'),
                        'monthly_price' => '34.99',
                        'annual_price' => '29.99',

                        'button_text' => esc_html__('Order Now', 'elementor-hosting-pricing'),
                        'featured' => 'yes',
                        'bottom_text' => esc_html__('Launch your e-commerce today.', 'elementor-hosting-pricing'),
                    ],
                    [
                        'plan_name' => esc_html__('Online Store + Website', 'elementor-hosting-pricing'),
                        'subtitle' => '<b>' . esc_html__('Comprehensive Presence', 'elementor-hosting-pricing') . '</b>: ' . esc_html__('Combine the best of both worlds. This all-inclusive plan provides you with a complete web presence for launching your e-commerce.', 'elementor-hosting-pricing'),
                        'monthly_price' => '49.99',
                        'annual_price' => '39.99',

                        'button_text' => esc_html__('Order Now', 'elementor-hosting-pricing'),
                        'bottom_text' => esc_html__('Comprehensive online solution.', 'elementor-hosting-pricing'),
                    ],
                ],
                'title_field' => '{{{ plan_name }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register style controls.
     */
    protected function register_style_controls() {
        // Layout
        $this->start_controls_section(
            'section_layout_style',
            [
                'label' => esc_html__('Layout', 'elementor-hosting-pricing'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columns', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plans' => '--hp-columns: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'plans_gap',
            [
                'label' => esc_html__('Gap', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [ 'min' => 0, 'max' => 80 ],
                ],
                'default' => [ 'unit' => 'px', 'size' => 30 ],
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plans' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'plans_align',
            [
                'label' => esc_html__('Card Alignment', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'elementor-hosting-pricing'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor-hosting-pricing'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'elementor-hosting-pricing'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'plan_padding',
            [
                'label' => esc_html__('Card Padding', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'plan_border_radius',
            [
                'label' => esc_html__('Card Border Radius', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'plan_border_width',
            [
                'label' => esc_html__('Card Border Width', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 0, 'max' => 10 ] ],
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan' => 'border-width: {{SIZE}}px;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'plan_box_shadow',
                'selector' => '{{WRAPPER}} .hosting-pricing-plan',
            ]
        );

        $this->add_control(
            'hover_effect',
            [
                'label' => esc_html__('Hover Effect', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'lift',
                'options' => [
                    'lift' => esc_html__('Lift', 'elementor-hosting-pricing'),
                    'zoom' => esc_html__('Zoom', 'elementor-hosting-pricing'),
                    'none' => esc_html__('None', 'elementor-hosting-pricing'),
                ],
                'prefix_class' => 'hp-hover-',
            ]
        );

        $this->add_control(
            'featured_scale',
            [
                'label' => esc_html__('Featured Plan Scale', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [ 'px' => [ 'min' => 1, 'max' => 1.2, 'step' => 0.01 ] ],
                'default' => [ 'size' => 1 ],
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan.featured' => '--hp-featured-scale: {{SIZE}};',
                ],
                'description' => esc_html__('Slightly enlarge the featured card to make it stand out.', 'elementor-hosting-pricing'),
            ]
        );

        $this->end_controls_section();

        // Typography
        $this->start_controls_section(
            'section_typography_style',
            [
                'label' => esc_html__('Typography', 'elementor-hosting-pricing'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'plan_name_typography',
                'label' => esc_html__('Plan Name', 'elementor-hosting-pricing'),
                'selector' => '{{WRAPPER}} .hosting-plan-name',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'plan_subtitle_typography',
                'label' => esc_html__('Subtitle', 'elementor-hosting-pricing'),
                'selector' => '{{WRAPPER}} .hosting-plan-subtitle',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => esc_html__('Price', 'elementor-hosting-pricing'),
                'selector' => '{{WRAPPER}} .hosting-price-amount',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_typography',
                'label' => esc_html__('Button', 'elementor-hosting-pricing'),
                'selector' => '{{WRAPPER}} .hosting-button',
            ]
        );

        $this->end_controls_section();

        // Toggle Style
        $this->start_controls_section(
            'section_toggle_style',
            [
                'label' => esc_html__('Toggle Switch', 'elementor-hosting-pricing'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'toggle_background',
            [
                'label' => esc_html__('Background Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-billing-toggle' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_text_color',
            [
                'label' => esc_html__('Text Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-billing-option' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_active_text_color',
            [
                'label' => esc_html__('Active Text Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-billing-option.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_switch_color',
            [
                'label' => esc_html__('Switch Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-slider' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'toggle_switch_active_color',
            [
                'label' => esc_html__('Switch Active Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-billing-toggle-input:checked + .hosting-slider' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Plan Style
        $this->start_controls_section(
            'section_plan_style',
            [
                'label' => esc_html__('Pricing Plans', 'elementor-hosting-pricing'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'plan_background',
            [
                'label' => esc_html__('Background Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'plan_border_color',
            [
                'label' => esc_html__('Border Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'featured_plan_background',
            [
                'label' => esc_html__('Featured Plan Background', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan.featured' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'featured_plan_border_color',
            [
                'label' => esc_html__('Featured Plan Border Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-pricing-plan.featured' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'plan_name_color',
            [
                'label' => esc_html__('Plan Name Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-plan-name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'plan_subtitle_color',
            [
                'label' => esc_html__('Subtitle Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-plan-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label' => esc_html__('Price Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'billing_cycle_color',
            [
                'label' => esc_html__('Billing Cycle Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-billing-cycle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'features_color',
            [
                'label' => esc_html__('Features Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-plan-feature' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background',
            [
                'label' => esc_html__('Button Background', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => esc_html__('Button Text Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_background',
            [
                'label' => esc_html__('Button Hover Background', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-button:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_text_color',
            [
                'label' => esc_html__('Button Hover Text Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'bottom_text_color',
            [
                'label' => esc_html__('Bottom Text Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-bottom-text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_background',
            [
                'label' => esc_html__('Badge Background', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-plan-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'badge_text_color',
            [
                'label' => esc_html__('Badge Text Color', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hosting-plan-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Split a textarea value into a clean list of feature lines.
     *
     * @param string $raw Raw textarea content.
     * @return array
     */
    protected function get_feature_lines( $raw ) {
        if ( empty( $raw ) ) {
            return [];
        }

        $lines = preg_split( '/\r\n|\r|\n/', $raw );

        return array_values( array_filter( array_map( 'trim', $lines ), 'strlen' ) );
    }

    /**
     * Format a price with the configured decimals.
     *
     * @param mixed $value    Raw price.
     * @param int   $decimals Decimals to keep.
     * @return string
     */
    protected function format_price( $value, $decimals ) {
        return number_format( (float) $value, max( 0, (int) $decimals ), '.', '' );
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings  = $this->get_settings_for_display();
        $currency  = isset( $settings['currency_symbol'] ) && '' !== $settings['currency_symbol'] ? $settings['currency_symbol'] : '$';
        $position  = 'after' === ( $settings['currency_position'] ?? 'before' ) ? 'after' : 'before';
        $decimals  = isset( $settings['price_decimals'] ) && '' !== $settings['price_decimals'] ? (int) $settings['price_decimals'] : 2;
        $period    = $settings['period_label'] ?? '/mo';
        $prefix    = $settings['price_prefix_text'] ?? esc_html__( 'From', 'elementor-hosting-pricing' );
        $badge     = $settings['badge_text'] ?? esc_html__( 'Popular', 'elementor-hosting-pricing' );
        $billing   = 'annual' === ( $settings['default_billing'] ?? 'monthly' ) ? 'annual' : 'monthly';
        $is_annual = 'annual' === $billing;
        ?>
        <div class="hosting-pricing-widget"
             data-currency="<?php echo esc_attr( $currency ); ?>"
             data-currency-position="<?php echo esc_attr( $position ); ?>"
             data-decimals="<?php echo esc_attr( $decimals ); ?>"
             data-period="<?php echo esc_attr( $period ); ?>">
            <?php if ( 'yes' === ( $settings['show_toggle'] ?? 'yes' ) ) : ?>
            <div class="hosting-billing-toggle" data-billing="<?php echo esc_attr( $billing ); ?>">
                <span class="hosting-billing-option<?php echo $is_annual ? '' : ' active'; ?>" data-billing="monthly" role="button" aria-pressed="<?php echo $is_annual ? 'false' : 'true'; ?>" tabindex="0">
                    <?php echo esc_html( $settings['monthly_label'] ?? __( 'Monthly', 'elementor-hosting-pricing' ) ); ?>
                </span>
                <label class="hosting-switch">
                    <input type="checkbox" class="hosting-billing-toggle-input" <?php checked( $is_annual ); ?> aria-label="<?php echo esc_attr__('Toggle billing cycle', 'elementor-hosting-pricing'); ?>">
                    <span class="hosting-slider"></span>
                </label>
                <span class="hosting-billing-option<?php echo $is_annual ? ' active' : ''; ?>" data-billing="annual" role="button" aria-pressed="<?php echo $is_annual ? 'true' : 'false'; ?>" tabindex="0">
                    <?php echo esc_html( $settings['annual_label'] ?? __( 'Annual (Save 20%)', 'elementor-hosting-pricing' ) ); ?>
                </span>
            </div>
            <?php endif; ?>

            <div class="hosting-pricing-plans">
                <?php
                $plans = is_array( $settings['plans'] ) ? $settings['plans'] : [];

                foreach ($plans as $plan) :
                    $is_featured = ! empty( $plan['featured'] ) && 'yes' === $plan['featured'] ? 'featured' : '';

                    
                    // Calculate savings
                    $monthly_total = (float)$plan['monthly_price'] * 12;
                    $annual_total = (float)$plan['annual_price'] * 12;
                    $savings = $monthly_total - $annual_total;
                    ?>
                    <div class="hosting-pricing-plan <?php echo esc_attr($is_featured); ?>" 
                         data-monthly-price="<?php echo esc_attr($plan['monthly_price']); ?>"
                         data-annual-price="<?php echo esc_attr($plan['annual_price']); ?>">
                        <?php if ($is_featured && '' !== $badge) : ?>
                            <div class="hosting-plan-badge"><?php echo esc_html( $badge ); ?></div>
                        <?php endif; ?>

                        <div class="hosting-plan-header">
                            <h3 class="hosting-plan-name"><?php echo esc_html($plan['plan_name']); ?></h3>
                        </div>

                        <div class="hosting-plan-pricing">
                            <?php if ( '' !== $prefix ) : ?>
                                <div class="hosting-price-prefix"><?php echo esc_html( $prefix ); ?></div>
                            <?php endif; ?>
                            <?php
                            $price_rows = [
                                'monthly-price' => $plan['monthly_price'],
                                'annual-price'  => $plan['annual_price'],
                            ];
                            foreach ( $price_rows as $row_class => $row_price ) :
                                $active = ( 'annual-price' === $row_class ) === $is_annual ? ' active' : '';
                                ?>
                                <div class="hosting-price <?php echo esc_attr( $row_class . $active ); ?>">
                                    <?php if ( 'before' === $position ) : ?>
                                        <span class="hosting-price-currency"><?php echo esc_html( $currency ); ?></span>
                                    <?php endif; ?>
                                    <span class="hosting-price-amount"><?php echo esc_html( $this->format_price( $row_price, $decimals ) ); ?></span>
                                    <?php if ( 'after' === $position ) : ?>
                                        <span class="hosting-price-currency hosting-price-currency--after"><?php echo esc_html( $currency ); ?></span>
                                    <?php endif; ?>
                                    <span class="hosting-billing-cycle"><?php echo esc_html( $period ); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="hosting-pay-today"<?php echo $is_annual ? ' style="display:block;"' : ''; ?>>
                            <?php
                            printf(
                                esc_html__('Billed as %1$s%2$s per year', 'elementor-hosting-pricing'),
                                esc_html( 'before' === $position ? $currency : '' ),
                                '<span class="hosting-billed-amount">' . esc_html( $this->format_price( $annual_total, $decimals ) ) . '</span>' . esc_html( 'after' === $position ? $currency : '' )
                            );
                            ?>
                            <div class="hosting-annual-savings"<?php echo $is_annual ? ' style="display:block;"' : ''; ?>>
                                <?php
                                printf(
                                    esc_html__('Save %1$s%2$s/year', 'elementor-hosting-pricing'),
                                    esc_html( 'before' === $position ? $currency : '' ),
                                    '<span class="hosting-savings-amount">' . esc_html( $this->format_price( $savings, $decimals ) ) . '</span>' . esc_html( 'after' === $position ? $currency : '' )
                                );
                                ?>
                            </div>
                        </div>

                        <?php if (!empty($plan['subtitle'])) : ?>
                            <p class="hosting-plan-subtitle"><?php echo wp_kses_post($plan['subtitle']); ?></p>
                        <?php endif; ?>

                        <?php $features = $this->get_feature_lines( $plan['features'] ?? '' ); ?>
                        <?php if ( ! empty( $features ) ) : ?>
                            <ul class="hosting-plan-features">
                                <?php foreach ( $features as $feature ) : ?>
                                    <li class="hosting-plan-feature"><?php echo wp_kses_post( $feature ); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <a href="<?php echo esc_url( $plan['button_url']['url'] ?? '#' ); ?>"
                           class="hosting-button"
                           <?php echo !empty($plan['button_url']['is_external']) ? 'target="_blank"' : ''; ?>
                           <?php echo !empty($plan['button_url']['nofollow']) ? 'rel="nofollow"' : ''; ?>>
                            <?php echo esc_html($plan['button_text']); ?>
                        </a>
                        
                        <?php if (!empty($plan['bottom_text'])) : ?>
                            <p class="hosting-bottom-text"><?php echo esc_html($plan['bottom_text']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render widget output in the editor.
     */
    protected function content_template() {
        ?>
        <#
        view.addInlineEditingAttributes('title', 'none');
        view.addInlineEditingAttributes('description', 'basic');
        #>
        <#
            var currency = settings.currency_symbol || '$';
            var currencyAfter = 'after' === settings.currency_position;
            var decimals = '' === settings.price_decimals || undefined === settings.price_decimals ? 2 : parseInt(settings.price_decimals, 10);
            var period = undefined === settings.period_label ? '/mo' : settings.period_label;
            var isAnnual = 'annual' === settings.default_billing;
            var money = function(value) {
                var amount = (parseFloat(value) || 0).toFixed(decimals);
                return currencyAfter ? amount + currency : currency + amount;
            };
        #>
        <div class="hosting-pricing-widget">
            <# if ('yes' === settings.show_toggle) { #>
            <div class="hosting-billing-toggle" data-billing="{{ isAnnual ? 'annual' : 'monthly' }}">
                <span class="hosting-billing-option {{ isAnnual ? '' : 'active' }}" data-billing="monthly">
                    {{{ settings.monthly_label }}}
                </span>
                <label class="hosting-switch">
                    <input type="checkbox" class="hosting-billing-toggle-input">
                    <span class="hosting-slider"></span>
                </label>
                <span class="hosting-billing-option {{ isAnnual ? 'active' : '' }}" data-billing="annual">
                    {{{ settings.annual_label }}}
                </span>
            </div>
            <# } #>

            <div class="hosting-pricing-plans">
                <# _.each(settings.plans, function(plan) {
                    var is_featured = 'yes' === plan.featured ? 'featured' : '';

                    // Calculate savings
                    var monthly_total = parseFloat(plan.monthly_price) * 12;
                    var annual_total = parseFloat(plan.annual_price) * 12;
                    var savings = monthly_total - annual_total;
                    var features = (plan.features || '').split('\n').map(function(line) { return line.trim(); }).filter(Boolean);
                    #>
                    <div class="hosting-pricing-plan {{{ is_featured }}}">
                        <# if (is_featured && settings.badge_text) { #>
                            <div class="hosting-plan-badge">{{{ settings.badge_text }}}</div>
                        <# } #>

                        <div class="hosting-plan-header">
                            <h3 class="hosting-plan-name">{{{ plan.plan_name }}}</h3>
                        </div>

                        <div class="hosting-plan-pricing">
                            <# if (settings.price_prefix_text) { #>
                                <div class="hosting-price-prefix">{{{ settings.price_prefix_text }}}</div>
                            <# } #>
                            <div class="hosting-price monthly-price {{ isAnnual ? '' : 'active' }}">
                                <# if (!currencyAfter) { #><span class="hosting-price-currency">{{{ currency }}}</span><# } #>
                                <span class="hosting-price-amount">{{{ (parseFloat(plan.monthly_price) || 0).toFixed(decimals) }}}</span>
                                <# if (currencyAfter) { #><span class="hosting-price-currency hosting-price-currency--after">{{{ currency }}}</span><# } #>
                                <span class="hosting-billing-cycle">{{{ period }}}</span>
                            </div>
                            <div class="hosting-price annual-price {{ isAnnual ? 'active' : '' }}">
                                <# if (!currencyAfter) { #><span class="hosting-price-currency">{{{ currency }}}</span><# } #>
                                <span class="hosting-price-amount">{{{ (parseFloat(plan.annual_price) || 0).toFixed(decimals) }}}</span>
                                <# if (currencyAfter) { #><span class="hosting-price-currency hosting-price-currency--after">{{{ currency }}}</span><# } #>
                                <span class="hosting-billing-cycle">{{{ period }}}</span>
                            </div>
                        </div>
                        <div class="hosting-pay-today" style="display:{{ isAnnual ? 'block' : 'none' }};">
                            <?php printf(esc_html__('Billed as %s per year', 'elementor-hosting-pricing'), '{{{ money(annual_total) }}}'); ?>
                            <div class="hosting-annual-savings" style="display:{{ isAnnual ? 'block' : 'none' }};">
                                <?php printf(esc_html__('Save %s/year', 'elementor-hosting-pricing'), '{{{ money(savings) }}}'); ?>
                            </div>
                        </div>

                        <# if (plan.subtitle) { #>
                            <p class="hosting-plan-subtitle">{{{ plan.subtitle }}}</p>
                        <# } #>

                        <# if (features.length) { #>
                            <ul class="hosting-plan-features">
                                <# _.each(features, function(feature) { #>
                                    <li class="hosting-plan-feature">{{{ feature }}}</li>
                                <# }); #>
                            </ul>
                        <# } #>


                        <a href="{{ plan.button_url.url }}" class="hosting-button">
                            {{{ plan.button_text }}}
                        </a>

                        <# if (plan.bottom_text) { #>
                            <p class="hosting-bottom-text">{{{ plan.bottom_text }}}</p>
                        <# } #>
                    </div>
                <# }); #>
            </div>
        </div>
        <?php
    }
}