<?php
/**
 * Run FAQs Migration
 * Adds faqs JSON column and seeds FAQ data
 */

require_once __DIR__ . '/../config/database.php';

try {
    // Check if column exists
    $check = $db->fetch("SHOW COLUMNS FROM services WHERE Field = 'faqs'");
    if (!$check) {
        $db->query("ALTER TABLE services ADD COLUMN faqs JSON DEFAULT NULL AFTER technologies");
        echo "✓ 'faqs' column added successfully\n";
    } else {
        echo "• 'faqs' column already exists, skipping...\n";
    }

    // Seed FAQ data for each service category
    $faqs = [
        'development' => json_encode([
            ['q' => 'What is the typical timeline for a web development project?', 'a' => 'Timelines vary by complexity. A standard corporate website takes 5-10 business days, while a full e-commerce platform or ERP system can take 3-8 weeks depending on feature requirements.'],
            ['q' => 'Do you provide hosting and maintenance after launch?', 'a' => 'Yes! We offer hosting setup, ongoing maintenance packages, and technical support to keep your website or application running smoothly post-launch.'],
            ['q' => 'Can I see a demo or prototype before development begins?', 'a' => 'Absolutely. We create wireframes and interactive prototypes (via Figma) for your approval before writing a single line of code. This ensures the final product matches your vision.'],
            ['q' => 'What technologies do you use for development?', 'a' => 'We use modern, scalable technologies including React, Node.js, PHP (Laravel), Python, Flutter, MySQL, and MongoDB — choosing the best stack for each project.'],
            ['q' => 'Do you handle mobile app development as well?', 'a' => 'Yes. We develop cross-platform mobile applications using Flutter, serving both iOS and Android from a single codebase.']
        ]),
        'design' => json_encode([
            ['q' => 'How many design revisions do I get?', 'a' => 'We provide unlimited revisions during the design phase to ensure you are 100% satisfied. We don\'t stop until the design feels right to you.'],
            ['q' => 'What do I receive as final deliverables?', 'a' => 'You receive all source files (AI, PSD, Figma), high-resolution exports (PNG, SVG, PDF), brand guidelines, and a style guide. Nothing is held back.'],
            ['q' => 'Can you redesign my existing brand identity?', 'a' => 'Yes. We specialize in brand refreshes and complete rebranding projects. We\'ll study your current identity and market position to create something that takes your brand to the next level.'],
            ['q' => 'Do you offer social media templates and packages?', 'a' => 'Yes! We create comprehensive social media kits including post templates, story templates, cover photos, and highlight icons — all consistent with your brand identity.'],
            ['q' => 'How long does a full branding package take?', 'a' => 'A complete branding package (logo, identity, guidelines, and stationery) typically takes 5-10 business days. Rush delivery is available on request.']
        ]),
        'academic' => json_encode([
            ['q' => 'What types of academic projects do you handle?', 'a' => 'We handle a wide range including graduation projects, programming assignments, research papers, theses, presentations, and technical reports across various fields of study.'],
            ['q' => 'Is the work original and plagiarism-free?', 'a' => 'Absolutely. Every project is written from scratch, checked with plagiarism detection tools, and delivered with an originality report upon request.'],
            ['q' => 'How do you ensure confidentiality?', 'a' => 'Your identity, project details, and all communications are strictly confidential. We never reuse or share your work. Complete privacy is guaranteed.'],
            ['q' => 'Can you work within tight deadlines?', 'a' => 'Yes. We offer rush delivery for urgent assignments. Depending on complexity, we can deliver certain projects within 24-48 hours.'],
            ['q' => 'Do you provide one-on-one tutoring sessions?', 'a' => 'Yes. We offer personalized tutoring sessions via video call for difficult subjects, programming concepts, and exam preparation. These are scheduled at your convenience.']
        ]),
        'documents' => json_encode([
            ['q' => 'What information do you need to write my CV?', 'a' => 'We need your work history, education, skills, achievements, and the type of roles you\'re targeting. The more details you provide, the stronger your CV will be.'],
            ['q' => 'Are your CVs ATS-friendly?', 'a' => 'Yes. All our CVs are optimized for Applicant Tracking Systems (ATS) while maintaining a clean, professional design. You get the best of both worlds.'],
            ['q' => 'Can you write a full business plan from scratch?', 'a' => 'Absolutely. We research your industry, market, competitors, and financial projections to deliver a comprehensive, investor-ready business plan.'],
            ['q' => 'How long does a feasibility study take?', 'a' => 'A standard feasibility study takes 7-10 business days. For more complex projects with extensive market research, it may take up to 14 days.'],
            ['q' => 'What\'s included in LinkedIn optimization?', 'a' => 'We rewrite your headline, about section, experience descriptions, skills, and recommendations. We also optimize for keywords to help recruiters find you.']
        ])
    ];

    foreach ($faqs as $key => $faq_json) {
        $db->query("UPDATE services SET faqs = :faqs WHERE category_key = :key", [
            'faqs' => $faq_json,
            'key' => $key
        ]);
        echo "✓ FAQs seeded for '{$key}'\n";
    }

    echo "\n✅ Migration completed successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
