-- Services Table for CyberX (4 Service Categories)
-- Run this migration to add the services table

CREATE TABLE IF NOT EXISTS services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_key VARCHAR(50) NOT NULL UNIQUE,
    title VARCHAR(255) NOT NULL,
    subtitle VARCHAR(255),
    icon VARCHAR(100) DEFAULT 'fa-cogs',
    gradient VARCHAR(100),
    color VARCHAR(50),
    description TEXT,
    features JSON,
    technologies JSON,
    status ENUM('active', 'inactive') DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Insert the 4 default service categories
INSERT INTO services (category_key, title, subtitle, icon, gradient, color, description, features, technologies, sort_order) VALUES
(
    'development',
    'Build Your Digital Presence',
    'Web & Software Development',
    'fa-code',
    'from-neon-blue to-neon-cyan',
    'neon-blue',
    'Transform your ideas into powerful digital solutions. We build modern, scalable, and user-friendly applications that drive business growth.',
    '[{"icon": "fa-shopping-cart", "title": "E-Commerce Solutions", "desc": "Salla, Shopify, WooCommerce stores with payment integration"}, {"icon": "fa-laptop-code", "title": "Custom Web Applications", "desc": "Tailored solutions built with modern frameworks"}, {"icon": "fa-cogs", "title": "ERP Systems", "desc": "Business management systems for inventory, HR, and accounting"}, {"icon": "fa-globe", "title": "Corporate Websites", "desc": "Professional websites with SEO optimization"}, {"icon": "fa-mobile-alt", "title": "Mobile Applications", "desc": "iOS & Android apps for your business"}, {"icon": "fa-database", "title": "API Development", "desc": "RESTful APIs and backend services"}]',
    '["React", "Node.js", "PHP", "Python", "Laravel", "Flutter", "MySQL", "MongoDB"]',
    1
),
(
    'design',
    'Make Your Brand Unforgettable',
    'Creative Design & Branding',
    'fa-palette',
    'from-neon-purple to-pink-500',
    'neon-purple',
    'Create a stunning visual identity that captures your brand essence and leaves a lasting impression on your audience.',
    '[{"icon": "fa-pen-nib", "title": "Logo Design", "desc": "Unique, memorable logos that represent your brand"}, {"icon": "fa-swatchbook", "title": "Visual Identity", "desc": "Complete brand guidelines, colors, and typography"}, {"icon": "fa-hashtag", "title": "Social Media Design", "desc": "Templates, posts, stories, and banners"}, {"icon": "fa-video", "title": "Motion Graphics", "desc": "Animated logos, intros, and promotional videos"}, {"icon": "fa-film", "title": "Video Editing", "desc": "Professional video production and editing"}, {"icon": "fa-ad", "title": "Marketing Materials", "desc": "Flyers, brochures, business cards, and more"}]',
    '["Photoshop", "Illustrator", "After Effects", "Premiere Pro", "Figma", "Canva"]',
    2
),
(
    'academic',
    'Excel in Your Studies',
    'Academic Support & Research',
    'fa-graduation-cap',
    'from-green-500 to-emerald-500',
    'green-400',
    'Get expert assistance with your academic projects, research, and assignments. We help students achieve academic excellence.',
    '[{"icon": "fa-project-diagram", "title": "Graduation Projects", "desc": "Complete project development with documentation"}, {"icon": "fa-tasks", "title": "Assignment Help", "desc": "Programming, design, and written assignments"}, {"icon": "fa-search", "title": "Research Consulting", "desc": "Literature review, methodology, and analysis"}, {"icon": "fa-book", "title": "Thesis Guidance", "desc": "Masters and PhD thesis support"}, {"icon": "fa-chalkboard-teacher", "title": "Tutoring", "desc": "One-on-one sessions for difficult subjects"}, {"icon": "fa-file-powerpoint", "title": "Presentation Design", "desc": "Professional PowerPoint and pitch decks"}]',
    '["SPSS", "MATLAB", "Python", "R", "LaTeX", "PowerPoint"]',
    3
),
(
    'documents',
    'Professional Documentation',
    'Business & Career Documents',
    'fa-file-alt',
    'from-orange-500 to-amber-500',
    'orange-400',
    'Get professionally crafted documents that help you secure funding, land jobs, and grow your business.',
    '[{"icon": "fa-chart-line", "title": "Feasibility Studies", "desc": "Comprehensive market analysis and projections"}, {"icon": "fa-file-invoice", "title": "Business Plans", "desc": "Investor-ready business proposals"}, {"icon": "fa-id-card", "title": "CV/Resume Writing", "desc": "ATS-optimized resumes that get interviews"}, {"icon": "fa-linkedin", "title": "LinkedIn Optimization", "desc": "Profile makeovers for career growth"}, {"icon": "fa-file-word", "title": "Office Documents", "desc": "Reports, memos, proposals, and more"}, {"icon": "fa-envelope-open-text", "title": "Cover Letters", "desc": "Compelling letters tailored to each role"}]',
    '["Word", "Excel", "PowerPoint", "Google Docs", "Canva", "Notion"]',
    4
);
