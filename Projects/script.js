const projects = [
  {
    id: 1,
    title: "Modern Residential Villa",
    type: "Residential",
    location: "Varanasi",
    duration: "6 months",
    budget: "₹50 Lakh",
    features: ["Eco-friendly design", "Modern architecture"],
    image: "https://i.pinimg.com/736x/40/20/f8/4020f81c0722bf956deb3a91c8917c08.jpg",
    testimonials: "Amazing experience working with Modernarch!",
  },
  {
    id: 2,
    title: "Luxury Apartment Complex",
    type: "Residential",
    location: "Noida",
    duration: "1 year",
    budget: "₹80 Lakh",
    features: ["Luxury amenities", "Spacious design"],
    image: "https://media.istockphoto.com/id/1165384568/photo/europe-modern-complex-of-residential-buildings.jpg?s=612x612&w=0&k=20&c=iW4NBiMPKEuvaA7h8wIsPHikhS64eR-5EVPfjQ9GPOA=",
    testimonials: "A wonderful project with exceptional design!",
  },
  {
    id: 3,
    title: "Corporate Office Tower 1",
    type: "Commercial",
    location: "Delhi",
    duration: "1 year",
    budget: "₹2 Crore",
    features: ["Energy-efficient", "Futuristic design"],
    image: "https://media.licdn.com/dms/image/v2/D5612AQGCBpquMF6udA/article-cover_image-shrink_720_1280/article-cover_image-shrink_720_1280/0/1725251367047?e=2147483647&v=beta&t=hDxkL780t8Tx_6ePhw6PmC_jDBWez50MJ7rbuK2iJjI",
    testimonials: "Top-notch professionalism and quality!",
  },
  {
    id: 4,
    title: "Smart Office Building",
    type: "Commercial",
    location: "Mumbai",
    duration: "8 months",
    budget: "₹1.5 Crore",
    features: ["Smart tech integration", "Sustainable building"],
    image: "https://www.deos-ag.com/wp-content/uploads/2023/02/deos-ag-unternehmen-DEOS_Gebaeude_Ulrich_Wozniak.jpg",
    testimonials: "Incredible design and innovation!",
  },
  {
    id: 5,
    title: "Luxury Hotel",
    type: "Commercial",
    location: "Goa",
    duration: "2 years",
    budget: "₹5 Crore",
    features: ["Eco-friendly", "Five-star amenities"],
    image: "https://r1imghtlak.mmtcdn.com/57663784996211e8bfae0adfcdb46c1c.jpg?&output-quality=75&downsize=910:612&crop=910:612;4,0&output-format=webp",
    testimonials: "Great experience creating a landmark building!",
  },
  {
    id: 6,
    title: "Shopping Mall",
    type: "Commercial",
    location: "Bangalore",
    duration: "1.5 years",
    budget: "₹3 Crore",
    features: ["Retail space", "Entertainment zone"],
    image: "https://www.skaindia.co.in/blog/wp-content/uploads/2024/11/shop-for-sale-in-ghaziabad-1038x576.jpeg",
    testimonials: "A state-of-the-art mall that attracts huge footfall!",
  }
];

// Function to display projects
function displayProjects(filter = 'All') {
  const projectsGrid = document.getElementById('projects-grid');
  projectsGrid.innerHTML = '';

  const filteredProjects = filter === 'All' ? projects : projects.filter(project => project.category === filter);

  // Check if there are filtered projects and display a message if none
  if (filteredProjects.length === 0) {
    projectsGrid.innerHTML = `<p>No projects available for the selected filter.</p>`;
    return;
  }

  filteredProjects.forEach(project => {
    const projectCard = document.createElement('div');
    projectCard.classList.add('project-card');

    projectCard.innerHTML = `
          <img src="${project.image}" alt="${project.category}" />
          <div class="project-card-content">
              <h2>${project.title}</h2>
              <p><strong>Client Name:</strong> ${project.client_name}</p>
              <p><strong>Location:</strong> ${project.location}</p>
              <p><strong>Duration:</strong> ${project.duration}</p>
              <p><strong>Budget:</strong> ${project.budget}</p>
              <p><strong>Features:</strong> ${project.features.join(', ')}</p>
          </div>
      `;

    projectsGrid.appendChild(projectCard);
  });
}

// Function to filter projects
function filterProjects(category) {
  displayProjects(category);
}

// Initial display of all projects
displayProjects('All');
