import { useState } from 'react'
import './App.css'
import { HashRouter } from 'react-router-dom'
import Rooms from './pages/Room'

function App() {
  return (
    <HashRouter>
      <Route path="/rooms" element={<Rooms/>}/>  
    </HashRouter>
  )
}

export default App
