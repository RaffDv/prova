export async function POST(endpoint, data) {
  return await fetch(`http://localhost:4000/${endpoint}`, {
    body: data,
    method: "POST",
    mode: 'cors'
  })
}

export async function GET(endpoint) {
  return await fetch(`http://localhost:4000/${endpoint}`, {
    method: "GET",
    mode: 'cors'
  })
}