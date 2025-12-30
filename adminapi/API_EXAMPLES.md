# API Usage Examples

This file contains practical examples of how to use the Wirasat Admin API.

## Configuration

First, make sure you have the correct API token. Update it in `config.php`:
```php
define('API_TOKEN', 'your_secure_token_here');
```

## cURL Examples

### Get All Projects
```bash
curl -X GET "http://localhost/wirasat/adminapi/projects" \
  -H "X-API-Token: wirasat_api_token_2024_secure_key_change_this"
```

### Get Project by ID
```bash
curl -X GET "http://localhost/wirasat/adminapi/projects?id=1" \
  -H "X-API-Token: wirasat_api_token_2024_secure_key_change_this"
```

### Get Active Services Only
```bash
curl -X GET "http://localhost/wirasat/adminapi/our-services?active_only=1" \
  -H "X-API-Token: wirasat_api_token_2024_secure_key_change_this"
```

### Get Latest News (Limited to 5)
```bash
curl -X GET "http://localhost/wirasat/adminapi/news-updates?active_only=1&limit=5" \
  -H "X-API-Token: wirasat_api_token_2024_secure_key_change_this"
```

### Get Unread Contact Messages
```bash
curl -X GET "http://localhost/wirasat/adminapi/contact-messages?unread_only=1" \
  -H "X-API-Token: wirasat_api_token_2024_secure_key_change_this"
```

## JavaScript Examples

### Fetch API
```javascript
const API_BASE_URL = 'http://localhost/wirasat/adminapi';
const API_TOKEN = 'wirasat_api_token_2024_secure_key_change_this';

// Get all projects
async function getProjects() {
  try {
    const response = await fetch(`${API_BASE_URL}/projects`, {
      method: 'GET',
      headers: {
        'X-API-Token': API_TOKEN
      }
    });
    
    const data = await response.json();
    
    if (data.success) {
      console.log('Projects:', data.data);
      return data.data;
    } else {
      console.error('Error:', data.message);
      return null;
    }
  } catch (error) {
    console.error('Request failed:', error);
    return null;
  }
}

// Get project by ID
async function getProjectById(id) {
  try {
    const response = await fetch(`${API_BASE_URL}/projects?id=${id}`, {
      method: 'GET',
      headers: {
        'X-API-Token': API_TOKEN
      }
    });
    
    const data = await response.json();
    return data.success ? data.data : null;
  } catch (error) {
    console.error('Request failed:', error);
    return null;
  }
}

// Usage
getProjects().then(projects => {
  if (projects) {
    projects.forEach(project => {
      console.log(project.project_name);
    });
  }
});
```

### jQuery Example
```javascript
const API_BASE_URL = 'http://localhost/wirasat/adminapi';
const API_TOKEN = 'wirasat_api_token_2024_secure_key_change_this';

$.ajax({
  url: `${API_BASE_URL}/projects`,
  method: 'GET',
  headers: {
    'X-API-Token': API_TOKEN
  },
  success: function(response) {
    if (response.success) {
      console.log('Projects:', response.data);
    } else {
      console.error('Error:', response.message);
    }
  },
  error: function(xhr, status, error) {
    console.error('Request failed:', error);
  }
});
```

## PHP Examples

### Using cURL
```php
<?php
$apiBaseUrl = 'http://localhost/wirasat/adminapi';
$apiToken = 'wirasat_api_token_2024_secure_key_change_this';

function callApi($endpoint, $params = []) {
    global $apiBaseUrl, $apiToken;
    
    $url = $apiBaseUrl . '/' . $endpoint;
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'X-API-Token: ' . $apiToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        return json_decode($response, true);
    }
    
    return null;
}

// Get all projects
$projects = callApi('projects');
if ($projects && $projects['success']) {
    foreach ($projects['data'] as $project) {
        echo $project['project_name'] . "\n";
    }
}

// Get project by ID
$project = callApi('projects', ['id' => 1]);
if ($project && $project['success']) {
    echo $project['data']['project_name'];
}

// Get active services
$services = callApi('our-services', ['active_only' => 1]);
if ($services && $services['success']) {
    foreach ($services['data'] as $service) {
        echo $service['service_title'] . "\n";
    }
}
?>
```

### Using file_get_contents
```php
<?php
$apiBaseUrl = 'http://localhost/wirasat/adminapi';
$apiToken = 'wirasat_api_token_2024_secure_key_change_this';

function getApiData($endpoint, $params = []) {
    global $apiBaseUrl, $apiToken;
    
    $url = $apiBaseUrl . '/' . $endpoint;
    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }
    
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => [
                'X-API-Token: ' . $apiToken
            ]
        ]
    ]);
    
    $response = file_get_contents($url, false, $context);
    return json_decode($response, true);
}

// Usage
$projects = getApiData('projects');
if ($projects && $projects['success']) {
    print_r($projects['data']);
}
?>
```

## Python Example

```python
import requests

API_BASE_URL = 'http://localhost/wirasat/adminapi'
API_TOKEN = 'wirasat_api_token_2024_secure_key_change_this'

def call_api(endpoint, params=None):
    url = f"{API_BASE_URL}/{endpoint}"
    headers = {
        'X-API-Token': API_TOKEN
    }
    
    response = requests.get(url, headers=headers, params=params)
    
    if response.status_code == 200:
        return response.json()
    else:
        print(f"Error: {response.status_code}")
        return None

# Get all projects
projects = call_api('projects')
if projects and projects['success']:
    for project in projects['data']:
        print(project['project_name'])

# Get project by ID
project = call_api('projects', {'id': 1})
if project and project['success']:
    print(project['data']['project_name'])

# Get active services
services = call_api('our-services', {'active_only': 1})
if services and services['success']:
    for service in services['data']:
        print(service['service_title'])
```

## React Example

```jsx
import React, { useState, useEffect } from 'react';

const API_BASE_URL = 'http://localhost/wirasat/adminapi';
const API_TOKEN = 'wirasat_api_token_2024_secure_key_change_this';

function ProjectsList() {
  const [projects, setProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch(`${API_BASE_URL}/projects`, {
      method: 'GET',
      headers: {
        'X-API-Token': API_TOKEN
      }
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          setProjects(data.data);
        } else {
          setError(data.message);
        }
        setLoading(false);
      })
      .catch(err => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      <h2>Projects</h2>
      <ul>
        {projects.map(project => (
          <li key={project.id}>
            {project.project_name}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default ProjectsList;
```

## Vue.js Example

```vue
<template>
  <div>
    <h2>Projects</h2>
    <div v-if="loading">Loading...</div>
    <div v-else-if="error">Error: {{ error }}</div>
    <ul v-else>
      <li v-for="project in projects" :key="project.id">
        {{ project.project_name }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      projects: [],
      loading: true,
      error: null
    };
  },
  mounted() {
    this.fetchProjects();
  },
  methods: {
    async fetchProjects() {
      try {
        const response = await fetch('http://localhost/wirasat/adminapi/projects', {
          method: 'GET',
          headers: {
            'X-API-Token': 'wirasat_api_token_2024_secure_key_change_this'
          }
        });
        
        const data = await response.json();
        
        if (data.success) {
          this.projects = data.data;
        } else {
          this.error = data.message;
        }
      } catch (err) {
        this.error = err.message;
      } finally {
        this.loading = false;
      }
    }
  }
};
</script>
```

## Error Handling

Always check the `success` field in the response:

```javascript
const response = await fetch(url, options);
const data = await response.json();

if (data.success) {
  // Handle success
  console.log(data.data);
} else {
  // Handle error
  console.error('API Error:', data.message);
}
```

## Testing with Postman

1. Create a new GET request
2. Set URL: `http://localhost/wirasat/adminapi/projects`
3. Add header:
   - Key: `X-API-Token`
   - Value: `wirasat_api_token_2024_secure_key_change_this`
4. Send request

## Notes

- All endpoints only support GET requests
- Token must be included in every request
- File paths are automatically converted to full URLs
- All responses are in JSON format
- Timestamps are in UTC format

