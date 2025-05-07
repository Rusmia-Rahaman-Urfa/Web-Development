// Basic data storage (in-memory for simplicity)
const dashboardData = {
  gpa: 3.0,
  skills: ["Python (Beginner)"],
  run: "3 km",
  weights: "50 kg (Avg)",
  movies: ["Motivational Movie 1"],
  fact: "The \"International Space Station\" orbits Earth roughly 16 times per day.",
  goals: ["Improve GPA to 3.7 by next semester."],
};

// Function to toggle input visibility for metrics with single values
function toggleMetricInput(metricType) {
  const valueDisplay = document.getElementById(`${metricType}-value`);
  const updateControls = document.getElementById(`${metricType}-update-controls`);
  const parentElement = valueDisplay.parentNode;
  let updateButton = null;

  for (const child of parentElement.children) {
      if (child.tagName === 'BUTTON' && child.textContent.includes('Update')) {
          updateButton = child;
          break;
      }
  }

  if (updateControls.style.display === 'none') {
      valueDisplay.style.display = 'none';
      if (updateButton) {
          updateButton.style.display = 'none';
      }
      updateControls.style.display = 'block';
  } else {
      valueDisplay.style.display = 'block';
      if (updateButton) {
          updateButton.style.display = 'block';
      }
      updateControls.style.display = 'none';
  }
}
    


// Function to save updated metric values (run, weights)
function saveMetric(metricType) {
  const newInput = document.getElementById('new-${metricType}');
  const newValue = newInput.value.trim();

  if (newValue) {
      dashboardData[metricType] = newValue;
      document.getElementById('${metricType}-value').textContent = newValue;
      toggleMetricInput(metricType);
      console.log('${metricType.toUpperCase()} updated to:', dashboardData[metricType]);
  } else {
      alert('Please enter a valid value for ${metricType.toUpperCase()}.');
  }
}

// GPA Specific Functions
function toggleGpaInput(metricType) {
  const gpaValueDisplay = document.getElementById('gpa-value');
  const gpaUpdateControls = document.getElementById('gpa-update-controls');
  const parentElement = gpaValueDisplay.parentNode;
  let updateButton = null;

  for (const child of parentElement.children) {
      if (child.tagName === 'BUTTON' && child.textContent.includes('Update GPA')) {
          updateButton = child;
          break;
      }
  }

  if (gpaUpdateControls.style.display === 'none') {
      gpaValueDisplay.style.display = 'none';
      if (updateButton) {
          updateButton.style.display = 'none';
      }
      gpaUpdateControls.style.display = 'block';
  } else {
      gpaValueDisplay.style.display = 'block';
      if (updateButton) {
          updateButton.style.display = 'block';
      }
      gpaUpdateControls.style.display = 'none';
  }
}

function saveGpa() {
  const newGpaInput = document.getElementById('new-gpa');
  const newGpaValue = parseFloat(newGpaInput.value);

  if (!isNaN(newGpaValue)) {
      dashboardData.gpa = newGpaValue.toFixed(2);
      document.getElementById('gpa-value').textContent = dashboardData.gpa;
      toggleGpaInput('gpa');
      console.log("GPA updated to:", dashboardData.gpa);
  } else {
      alert("Please enter a valid number for GPA.");
  }
}

// Functions for lists (Skills, Movies, Goals)
function addSkill() {
  const newSkillInput = document.getElementById('new-skill');
  const newSkill = newSkillInput.value.trim();
  if (newSkill) {
      dashboardData.skills.push(newSkill);
      updateListDisplay('skills', 'skills-list');
      newSkillInput.value = '';
  }
}

function addMovie() {
  const newMovieInput = document.getElementById('new-movie');
  const newMovie = newMovieInput.value.trim();
  if (newMovie) {
      dashboardData.movies.push(newMovie);
      updateListDisplay('movies', 'movies-list');
      newMovieInput.value = '';
  }
}

function addGoal() {
  const newGoalInput = document.getElementById('new-goal');
  const newGoal = newGoalInput.value.trim();
  if (newGoal) {
      dashboardData.goals.push(newGoal);
      updateListDisplay('goals', 'goal-list');
      newGoalInput.value = '';
  }
}

function updateListDisplay(dataKey, listId) {
  const listElement = document.getElementById(listId);
  listElement.innerHTML = ''; // Clear the existing list
  dashboardData[dataKey].forEach(item => {
      const listItem = document.createElement('li');
      listItem.textContent = item;
      listElement.appendChild(listItem);
  });
}

// Function to fetch an interesting fact using OpenAI API
async function getInterestingFact() {
  const apiKey = 'Your_OpenAI_Key';


  // Replace with your actual API key
  const apiUrl = 'https://api.openai.com/v1/completions';

  try {
    const response = await fetch("https://api.openai.com/v1/chat/completions", {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${apiKey}`,
      },
      body: JSON.stringify({
          model: 'gpt-4-turbo', // Or 'gpt-3.5-turbo'
          messages: [{ role: "user", content: "Generate a single interesting fact." }],
          max_tokens: 100,
          temperature: 0.7,
      }),
      });

      if (!response.ok) {
          throw new Error('HTTP error! status: ${response.status}');
      }

      const data = await response.json();
      if (data.choices && data.choices.length > 0) {
          dashboardData.fact = data.choices[0].message.content;
          document.getElementById('fact-value').textContent = dashboardData.fact;
      } else {
          document.getElementById('fact-value').textContent = 'Failed to get fact.';
      }

  } catch (error) {
      console.error('Error fetching interesting fact:', error);
      document.getElementById('fact-value').textContent = 'Error fetching fact.';
  }
}

// Initial data display
document.getElementById('gpa-value').textContent = dashboardData.gpa;
updateListDisplay('skills', 'skills-list');
document.getElementById('run-value').textContent = dashboardData.run;
document.getElementById('weights-value').textContent = dashboardData.weights;
updateListDisplay('movies', 'movies-list');
document.getElementById('fact-value').textContent = dashboardData.fact;
updateListDisplay('goals', 'goal-list');
