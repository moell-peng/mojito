export default {
  admin: {
    authorize: {
      clientId: process.env.MIX_CLIENT_ID || 2,
      clientSecret: process.env.MIX_CLIENT_SECRET || ''
    },
    loginRouteName: 'adminLogin',

    dashboardName: 'adminDashboard',

    dashboardFullPath: '/admin/dashboard',

    appName: {
      fullName: process.env.MIX_APP_NAME || 'admin dashboard',
      abbrName: process.env.MIX_APP_ABBR_NAME || 'admin'
    },

    locale: process.env.MIX_APP_LOCALE || 'en'
  },

  guardNames: [
    {
      label: 'admin',
      value: 'admin'
    }
  ],

  apiUrl: '',

  //Unit is day
  tokenTTL: 1,

  //Unit is day
  refreshTokenTTL: 7,

  showAuthorGitHubUrl: true,
}