<?php
/**
 * Elementor Hosting Pricing Widget
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


        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'plan_name',
            [
                'label' => esc_html__('Plan Name', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Website', 'elementor-hosting-pricing'),
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
            ]
        );

        $repeater->add_control(
            'annual_price',
            [
                'label' => esc_html__('Annual Price', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => '19.99',
                'step' => '0.01',
            ]
        );



        $repeater->add_control(
            'button_text',
            [
                'label' => esc_html__('Button Text', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Order Now', 'elementor-hosting-pricing'),
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
            ]
        );

        $repeater->add_control(
            'bottom_text',
            [
                'label' => esc_html__('Bottom Text', 'elementor-hosting-pricing'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Optimize your online presence.', 'elementor-hosting-pricing'),
                'description' => esc_html__('This text appears below the button', 'elementor-hosting-pricing'),
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
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="hosting-pricing-widget">
            <div class="hosting-billing-toggle">
                <span class="hosting-billing-option active" data-billing="monthly">
                    <?php esc_html_e('Monthly', 'elementor-hosting-pricing'); ?>
                </span>
                <label class="hosting-switch">
                    <input type="checkbox" class="hosting-billing-toggle-input">
                    <span class="hosting-slider"></span>
                </label>
                <span class="hosting-billing-option" data-billing="annual">
                    <?php esc_html_e('Annual (Save 20%)', 'elementor-hosting-pricing'); ?>
                </span>
            </div>

            <div class="hosting-pricing-plans">
                <?php 
                $plans = $settings['plans'];
                
                foreach ($plans as $plan) : 
                    $is_featured = 'yes' === $plan['featured'] ? 'featured' : '';

                    
                    // Calculate savings
                    $monthly_total = (float)$plan['monthly_price'] * 12;
                    $annual_total = (float)$plan['annual_price'] * 12;
                    $savings = $monthly_total - $annual_total;
                    ?>
                    <div class="hosting-pricing-plan <?php echo esc_attr($is_featured); ?>" 
                         data-monthly-price="<?php echo esc_attr($plan['monthly_price']); ?>"
                         data-annual-price="<?php echo esc_attr($plan['annual_price']); ?>">
                        <?php if ($is_featured) : ?>
                            <div class="hosting-plan-badge"><?php esc_html_e('Popular', 'elementor-hosting-pricing'); ?></div>
                        <?php endif; ?>
                        
                        <div class="hosting-plan-header">
                            <h3 class="hosting-plan-name"><?php echo esc_html($plan['plan_name']); ?></h3>
                        </div>
                        
                        <div class="hosting-plan-pricing">
                            <div class="hosting-price-prefix"><?php esc_html_e('From', 'elementor-hosting-pricing'); ?></div>
                            <div class="hosting-price monthly-price active">
                                <span class="hosting-price-currency">$</span>
                                <span class="hosting-price-amount"><?php echo esc_html(number_format($plan['monthly_price'], 2)); ?></span>
                                <span class="hosting-billing-cycle">/mo</span>
                            </div>
                            <div class="hosting-price annual-price">
                                <span class="hosting-price-currency">$</span>
                                <span class="hosting-price-amount"><?php echo esc_html(number_format($plan['annual_price'], 2)); ?></span>
                                <span class="hosting-billing-cycle">/mo</span>
                            </div>
                        </div>
                        <div class="hosting-pay-today">
                            <?php 
                            printf(
                                esc_html__('Billed as $%s per year', 'elementor-hosting-pricing'),
                                '<span class="hosting-billed-amount">' . esc_html(number_format($annual_total, 2)) . '</span>'
                            );
                            ?>
                            <div class="hosting-annual-savings">
                                <?php
                                printf(
                                    esc_html__('Save $%s/year', 'elementor-hosting-pricing'),
                                    '<span class="hosting-savings-amount">' . esc_html(number_format($savings, 2)) . '</span>'
                                );
                                ?>
                            </div>
                        </div>

                        <?php if (!empty($plan['subtitle'])) : ?>
                            <p class="hosting-plan-subtitle"><?php echo wp_kses_post($plan['subtitle']); ?></p>
                        <?php endif; ?>
                        

                        <a href="<?php echo esc_url($plan['button_url']['url']); ?>" 
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
        <div class="hosting-pricing-widget">
            <div class="hosting-billing-toggle">
                <span class="hosting-billing-option active" data-billing="monthly">
                    <?php esc_html_e('Monthly', 'elementor-hosting-pricing'); ?>
                </span>
                <label class="hosting-switch">
                    <input type="checkbox" class="hosting-billing-toggle-input">
                    <span class="hosting-slider"></span>
                </label>
                <span class="hosting-billing-option" data-billing="annual">
                    <?php esc_html_e('Annual (Save 20%)', 'elementor-hosting-pricing'); ?>
                </span>
            </div>

            <div class="hosting-pricing-plans">
                <# _.each(settings.plans, function(plan) { 
                    var is_featured = 'yes' === plan.featured ? 'featured' : '';
                    
                    // Calculate savings
                    var monthly_total = parseFloat(plan.monthly_price) * 12;
                    var annual_total = parseFloat(plan.annual_price) * 12;
                    var savings = monthly_total - annual_total;
                    #>
                    <div class="hosting-pricing-plan {{{ is_featured }}}">
                        <# if (is_featured) { #>
                            <div class="hosting-plan-badge"><?php esc_html_e('Popular', 'elementor-hosting-pricing'); ?></div>
                        <# } #>
                        
                        <div class="hosting-plan-header">
                            <h3 class="hosting-plan-name">{{{ plan.plan_name }}}</h3>
                        </div>

                        <div class="hosting-plan-pricing">
                            <div class="hosting-price-prefix"><?php esc_html_e('From', 'elementor-hosting-pricing'); ?></div>
                            <div class="hosting-price monthly-price active">
                                <span class="hosting-price-currency">$</span>
                                <span class="hosting-price-amount">{{{ Number(plan.monthly_price).toFixed(2) }}}</span>
                                <span class="hosting-billing-cycle">/mo</span>
                            </div>
                            <div class="hosting-price annual-price">
                                <span class="hosting-price-currency">$</span>
                                <span class="hosting-price-amount">{{{ Number(plan.annual_price).toFixed(2) }}}</span>
                                <span class="hosting-billing-cycle">/mo</span>
                            </div>
                        </div>
                        <div class="hosting-pay-today">
                            <?php printf(esc_html__('Billed as $%s per year', 'elementor-hosting-pricing'), '{{{ (parseFloat(plan.annual_price) * 12).toFixed(2) }}}'); ?>
                            <div class="hosting-annual-savings">
                                <?php printf(esc_html__('Save $%s/year', 'elementor-hosting-pricing'), '{{{ (savings).toFixed(2) }}}'); ?>
                            </div>
                        </div>

                        <# if (plan.subtitle) { #>
                            <p class="hosting-plan-subtitle">{{{ plan.subtitle }}}</p>
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