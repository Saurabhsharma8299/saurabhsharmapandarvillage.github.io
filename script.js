// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Navbar background change on scroll
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
        navbar.style.background = 'linear-gradient(to right, #141618, #1a252f)';
    } else {
        navbar.style.background = 'linear-gradient(to right, #1a1c20, #2c3e50)';
    }
});

// Gallery images
const galleryImages = [
    'village1.jpg',
    'village2.jpg',
    'village3.jpg',
    'village4.jpg'
];

// Populate gallery
const galleryGrid = document.querySelector('.gallery-grid');
if (galleryGrid) {
    galleryImages.forEach(image => {
        const imgDiv = document.createElement('div');
        imgDiv.className = 'gallery-item';
        imgDiv.innerHTML = `<img src="images/${image}" alt="Pandar Village">`;
        galleryGrid.appendChild(imgDiv);
    });
}

// Form submission handling
const contactForm = document.querySelector('.contact-form form');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('submit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Message sent successfully!');
                this.reset();
            } else {
                alert('Error sending message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again later.');
        });
    });
}
