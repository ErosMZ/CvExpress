// Translations object
const translations = {
    es: {
        // Navigation
        profile: "Perfil",
        experience: "Experiencia",
        skills: "Competencias",
        languages: "Lenguajes",
        education: "Formación",
        projects: "Proyectos",
        contact: "Contacto",
        
        // Profile section
        profileTitle: "Perfil Profesional",
        profileText: "Desarrollador Full Stack apasionado por crear soluciones web innovadoras y eficientes. Con experiencia en tecnologías modernas y un enfoque en la calidad del código y la experiencia del usuario. Busco constantemente aprender y aplicar las mejores prácticas en desarrollo de software.",
        yearsExp: "Años Experiencia",
        projectsCount: "Proyectos",
        technologies: "Tecnologías",
        
        // Experience section
        experienceTitle: "Experiencia Laboral",
        
        // Skills section
        skillsTitle: "Competencias",
        frontend: "Frontend",
        backend: "Backend",
        tools: "Herramientas",
        
        // Languages section
        languagesTitle: "Lenguajes de Programación",
        programmingLanguages: "Lenguajes",
        frameworksLibraries: "Frameworks & Librerías",
        databases: "Bases de Datos",
        yearsShort: "años",
        
        // Education section
        educationTitle: "Formación Académica",
        
        // Projects section
        projectsTitle: "Proyectos Destacados",
        viewProject: "Ver proyecto",
        
        // Contact section
        contactTitle: "Contacto",
        contactSubtitle: "¡Hablemos!",
        contactText: "Estoy disponible para nuevas oportunidades y colaboraciones. No dudes en contactarme.",
        email: "Email",
        phone: "Teléfono",
        location: "Ubicación",
        locationText: "Valencia, España",
        name: "Nombre",
        subject: "Asunto",
        message: "Mensaje",
        sendMessage: "Enviar Mensaje"
    },
    en: {
        // Navigation
        profile: "Profile",
        experience: "Experience",
        skills: "Skills",
        languages: "Languages",
        education: "Education",
        projects: "Projects",
        contact: "Contact",
        
        // Profile section
        profileTitle: "Professional Profile",
        profileText: "Full Stack Developer passionate about creating innovative and efficient web solutions. With experience in modern technologies and a focus on code quality and user experience. I constantly seek to learn and apply best practices in software development.",
        yearsExp: "Years Experience",
        projectsCount: "Projects",
        technologies: "Technologies",
        
        // Experience section
        experienceTitle: "Work Experience",
        
        // Skills section
        skillsTitle: "Skills",
        frontend: "Frontend",
        backend: "Backend",
        tools: "Tools",
        
        // Languages section
        languagesTitle: "Programming Languages",
        programmingLanguages: "Languages",
        frameworksLibraries: "Frameworks & Libraries",
        databases: "Databases",
        yearsShort: "years",
        
        // Education section
        educationTitle: "Education",
        
        // Projects section
        projectsTitle: "Featured Projects",
        viewProject: "View project",
        
        // Contact section
        contactTitle: "Contact",
        contactSubtitle: "Let's talk!",
        contactText: "I'm available for new opportunities and collaborations. Feel free to contact me.",
        email: "Email",
        phone: "Phone",
        location: "Location",
        locationText: "Valencia, Spain",
        name: "Name",
        subject: "Subject",
        message: "Message",
        sendMessage: "Send Message"
    }
};

// Current language state
let currentLang = 'es';

// Function to translate the page
function translatePage(lang) {
    currentLang = lang;
    
    // Save language preference
    localStorage.setItem('preferredLanguage', lang);
    
    // Update all elements with data-lang attribute
    document.querySelectorAll('[data-lang]').forEach(element => {
        const key = element.getAttribute('data-lang');
        if (translations[lang][key]) {
            element.textContent = translations[lang][key];
        }
    });
    
    // Update language toggle button
    const langText = document.querySelector('.lang-text');
    if (langText) {
        langText.textContent = lang === 'es' ? 'EN' : 'ES';
    }
}

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    // Load saved language preference
    const savedLang = localStorage.getItem('preferredLanguage') || 'es';
    translatePage(savedLang);
    
    // Create mobile menu toggle button
    const menuToggle = document.createElement('button');
    menuToggle.className = 'menu-toggle';
    menuToggle.innerHTML = '<span></span><span></span><span></span>';
    menuToggle.setAttribute('aria-label', 'Toggle menu');
    document.body.appendChild(menuToggle);
    
    // Create menu overlay
    const menuOverlay = document.createElement('div');
    menuOverlay.className = 'menu-overlay';
    document.body.appendChild(menuOverlay);
    
    const leftPanel = document.querySelector('.left-panel');
    
    // Toggle menu function
    function toggleMenu() {
        menuToggle.classList.toggle('active');
        leftPanel.classList.toggle('active');
        menuOverlay.classList.toggle('active');
        document.body.style.overflow = leftPanel.classList.contains('active') ? 'hidden' : '';
    }
    
    // Menu toggle click
    menuToggle.addEventListener('click', toggleMenu);
    
    // Overlay click to close menu
    menuOverlay.addEventListener('click', function() {
        if (leftPanel.classList.contains('active')) {
            toggleMenu();
        }
    });
    
    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && leftPanel.classList.contains('active')) {
            toggleMenu();
        }
    });
    
    // Language toggle functionality
    const languageToggle = document.getElementById('languageToggle');
    if (languageToggle) {
        languageToggle.addEventListener('click', function() {
            const newLang = currentLang === 'es' ? 'en' : 'es';
            translatePage(newLang);
        });
    }
    
    // Navigation items
    const navItems = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('.content-section');
    
    // Handle navigation clicks
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items and sections
            navItems.forEach(nav => nav.classList.remove('active'));
            sections.forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Show corresponding section
            const targetSection = this.getAttribute('data-section');
            const section = document.getElementById(targetSection);
            
            if (section) {
                section.classList.add('active');
                
                // Smooth scroll to top of content
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
            
            // Close mobile menu after navigation
            if (window.innerWidth <= 768 && leftPanel.classList.contains('active')) {
                toggleMenu();
            }
        });
    });
    
    // Close menu when window is resized to desktop size
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && leftPanel.classList.contains('active')) {
            menuToggle.classList.remove('active');
            leftPanel.classList.remove('active');
            menuOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Animate skill bars when they come into view
    const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const skillBars = entry.target.querySelectorAll('.skill-progress');
                skillBars.forEach(bar => {
                    bar.style.width = bar.style.getPropertyValue('--progress');
                });
            }
        });
    }, observerOptions);
    
    const competenciasSection = document.getElementById('competencias');
    if (competenciasSection) {
        observer.observe(competenciasSection);
    }
    
    // Download CV button - now just a placeholder, implement your own logic
    const downloadBtn = document.querySelector('.download-cv');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            // Implement your download logic here
            // For example:
            // window.location.href = './docs/CV-Eros-Munoz.pdf';
            console.log('Download CV clicked');
        });
    }
    
    // Contact form submission
    const contactForm = document.querySelector('.contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(contactForm);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                subject: formData.get('subject'),
                message: formData.get('message')
            };
            
            // Implement your form submission logic here
            console.log('Form data:', data);
            
            // Reset form
            contactForm.reset();
        });
    }
    
    // Add parallax effect to header image
    let lastScrollY = window.scrollY;
    const profileImage = document.querySelector('.profile-image');
    
    window.addEventListener('scroll', function() {
        const scrollY = window.scrollY;
        
        if (profileImage && scrollY < 800) {
            profileImage.style.transform = `translateY(${scrollY * 0.5}px)`;
        }
        
        lastScrollY = scrollY;
    });
    
    // Add typing animation to name
    const nameElement = document.querySelector('.name');
    if (nameElement) {
        const originalText = nameElement.textContent;
        nameElement.textContent = '';
        let charIndex = 0;
        
        function typeWriter() {
            if (charIndex < originalText.length) {
                nameElement.textContent += originalText.charAt(charIndex);
                charIndex++;
                setTimeout(typeWriter, 80);
            }
        }
        
        // Start typing animation after a short delay
        setTimeout(typeWriter, 500);
    }
    
    // Add fade-in animation to timeline items
    const timelineItems = document.querySelectorAll('.timeline-item');
    const timelineObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '0';
                entry.target.style.transform = 'translateX(-30px)';
                
                setTimeout(() => {
                    entry.target.style.transition = 'all 0.6s ease';
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateX(0)';
                }, 100);
                
                timelineObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    
    timelineItems.forEach(item => {
        timelineObserver.observe(item);
    });
    
    // Add intersection observer for stats counter animation
    const statNumbers = document.querySelectorAll('.stat-number');
    const statsObserver = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const target = entry.target;
                const finalValue = target.textContent;
                const numericValue = parseInt(finalValue);
                
                if (!isNaN(numericValue)) {
                    let currentValue = 0;
                    const increment = numericValue / 50;
                    const suffix = finalValue.replace(/[0-9]/g, '');
                    
                    const counter = setInterval(() => {
                        currentValue += increment;
                        if (currentValue >= numericValue) {
                            target.textContent = finalValue;
                            clearInterval(counter);
                        } else {
                            target.textContent = Math.floor(currentValue) + suffix;
                        }
                    }, 30);
                }
                
                statsObserver.unobserve(target);
            }
        });
    }, { threshold: 0.5 });
    
    statNumbers.forEach(stat => {
        statsObserver.observe(stat);
    });
});

// Page load animation
window.addEventListener('load', function() {
    document.body.style.opacity = '0';
    setTimeout(() => {
        document.body.style.transition = 'opacity 0.5s ease';
        document.body.style.opacity = '1';
    }, 100);
});

// Handle browser back/forward buttons
window.addEventListener('popstate', function(e) {
    if (e.state && e.state.section) {
        const navItem = document.querySelector(`[data-section="${e.state.section}"]`);
        if (navItem) {
            navItem.click();
        }
    }
});

// Add history state when section changes
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function() {
        const section = this.getAttribute('data-section');
        history.pushState({ section: section }, '', `#${section}`);
    });
});

// Initialize the correct section based on URL hash
window.addEventListener('DOMContentLoaded', function() {
    const hash = window.location.hash.substring(1);
    if (hash) {
        const navItem = document.querySelector(`[data-section="${hash}"]`);
        if (navItem) {
            setTimeout(() => navItem.click(), 100);
        }
    }
});