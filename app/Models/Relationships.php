<?php

/*
 * RELATIONSHIPS SUMMARY
 * 
 * User
 *   - belongsToMany roles
 *   - hasMany websites
 *   - hasMany subscriptions
 *   - hasMany payments
 *   - hasMany posts
 *   - hasMany comments
 *   - hasMany media
 *   - hasMany tickets
 *   - hasMany notifications
 *   - hasMany activities
 *
 * Role
 *   - belongsToMany users
 *   - belongsToMany permissions
 *
 * Permission
 *   - belongsToMany roles
 *
 * Plan
 *   - hasMany subscriptions
 *
 * Subscription
 *   - belongsTo user
 *   - belongsTo plan
 *   - hasMany payments
 *   - hasMany usageMetrics
 *
 * Payment
 *   - belongsTo user
 *   - belongsTo subscription
 *
 * Website
 *   - belongsTo user
 *   - hasMany pages
 *   - hasMany menus
 *   - hasMany posts
 *   - hasMany categories
 *   - hasMany tags
 *   - hasMany media
 *   - hasMany galleries
 *   - hasMany forms
 *   - hasMany domains
 *   - belongsToMany themes
 *   - hasMany settings
 *   - hasMany analytics
 *   - hasMany testimonials
 *   - hasMany teamMembers
 *   - hasMany services
 *   - hasMany portfolios
 *   - hasMany contacts
 *   - hasMany newsletters
 *   - hasMany faqs
 *
 * Page
 *   - belongsTo website
 *   - belongsTo template
 *   - belongsTo parent (self)
 *   - hasMany children (self)
 *   - hasMany sections
 *
 * Section
 *   - belongsTo page
 *   - hasMany blocks
 *
 * Block
 *   - belongsTo section
 *
 * Post
 *   - belongsTo website
 *   - belongsTo author (user)
 *   - belongsToMany categories
 *   - belongsToMany tags
 *   - hasMany comments
 *
 * Comment
 *   - belongsTo post
 *   - belongsTo user (optional)
 *   - belongsTo parent (self)
 *   - hasMany replies (self)
 *
 * Media
 *   - belongsTo website
 *   - belongsTo user
 *   - belongsToMany galleries
 *
 * Gallery
 *   - belongsTo website
 *   - belongsToMany media
 *
 * Form
 *   - belongsTo website
 *   - hasMany submissions
 *
 * FormSubmission
 *   - belongsTo form
 *
 * Ticket
 *   - belongsTo user
 *   - belongsTo assignedTo (user)
 *   - hasMany replies
 *
 * TicketReply
 *   - belongsTo ticket
 *   - belongsTo user
 *
 * Activity
 *   - belongsTo user
 *   - morphTo subject
 *
 * Notification
 *   - belongsTo user
 *
 * Setting
 *   - (global settings, no relationships)
 *
 * WebsiteSetting
 *   - belongsTo website
 *
 * Theme
 *   - belongsToMany websites
 *
 * Domain
 *   - belongsTo website
 *
 * Testimonial
 *   - belongsTo website
 *
 * TeamMember
 *   - belongsTo website
 *
 * Service
 *   - belongsTo website
 *
 * Portfolio
 *   - belongsTo website
 *   - belongsToMany categories
 *
 * Contact
 *   - belongsTo website
 *
 * Newsletter
 *   - belongsTo website
 *   - belongsToMany campaigns
 *
 * NewsletterCampaign
 *   - belongsTo website
 *   - belongsToMany subscribers
 *
 * Faq
 *   - belongsTo website
 *
 * Category
 *   - belongsTo website
 *   - belongsTo parent (self)
 *   - hasMany children (self)
 *   - belongsToMany posts
 *   - belongsToMany portfolios
 *
 * Tag
 *   - belongsTo website
 *   - belongsToMany posts
 *
 * Analytic
 *   - belongsTo website
 *
 * Template
 *   - (global templates, hasMany pages)
 *
 * UsageMetric
 *   - belongsTo subscription
 */