const mainWindow = new BrowserWindow({
  width: 500,
  height: 500,
  frame: true, // set to false for frameless
  roundedCorners: true, // Electron >= 21
  webPreferences: {
    nodeIntegration: true
  }
});
