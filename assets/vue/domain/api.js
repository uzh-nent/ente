import {iriToId, restClient} from "../services/api";

export const router = {
    probesView: function (query = {}) {
        return restClient._getFullUrl('/probes/all', query)
    },
    probeView: function (probe) {
        return '/probes/all/' + iriToId(probe['@id']) + '/view'
    },
    probeActiveView: function (probe) {
        return '/probes/active/' + iriToId(probe['@id']) + '/view'
    },
    probeWorksheetPdf: function (probe) {
        return '/probes/active/' + iriToId(probe['@id']) + '/worksheet.pdf'
    },
    reportPdf: function (report) {
        return '/reports/' + iriToId(report['@id']) + '/download/' + report.filename
    },
    pre2026ProbesExport: function (laboratoryFunction) {
        return '/probes/export/pre2026?laboratoryFunction=' + laboratoryFunction
    }
}

export const preloadApi = {
    getNewProbe: function () {
        return {
            specimens: window.specimens.member,
        }
    },
    getActiveProbes: function () {
        return {
            activeProbes: window.activeProbes.member,
        }
    },
    getAllProbes: function () {
        return {
            organisms: window.organisms.member,
            specimens: window.specimens.member,
            users: window.users.member,
        }
    },
    getViewActiveProbe: function () {
        return {
            probe: window.probe,

            observations: window.observations.member,
            elmReports: window.elmReports.member,
            reports: window.reports.member,
            reportEmails: window.reportEmails.member,

            specimens: window.specimens.member,
            leadingCodes: window.leadingCodes.member,
            organisms: window.organisms.member,

            users: window.users.member,
            interpretationTexts: window.interpretationTexts.member,
            reportTexts: window.reportTexts.member,
        }
    },
}

export const api = {
    get: function (id) {
        return restClient.get(id)
    },
    getProbesExcel: function (query) {
        query['pagination'] = 0
        const url = restClient._getFullUrl('/api/probes', query)
        return restClient.getExcel(url)
    },
    getInvoicesPatientsExcel: function (query) {
        const url = restClient._getFullUrl('/invoices/patients', query)
        return restClient.getExcel(url)
    },
    getInvoicesOrderersExcel: function (query) {
        const url = restClient._getFullUrl('/invoices/orderers', query)
        return restClient.getExcel(url)
    },
    poll: function (entity) {
        return restClient.poll(entity)
    },
    patch: function (instance, patch) {
        return restClient.patch(instance, patch)
    },
    delete: function (instance) {
        return restClient.delete(instance)
    },
    getOpenProbes: function (query) {
        query['pagination'] = 0
        query['exists[finishedAt]'] = 0
        return restClient.getCollection('/api/probes', query)
    },
    getPaginatedProbes: function (query) {
        query['collections'] = 1
        return restClient.getPaginatedCollection('/api/probes', query)
    },
    getPaginatedOrganisations: function (query) {
        return restClient.getPaginatedCollection('/api/organizations', query)
    },
    getPaginatedPractitioners: function (query) {
        return restClient.getPaginatedCollection('/api/practitioners', query)
    },
    getPaginatedPatients: function (query) {
        return restClient.getPaginatedCollection('/api/patients', query)
    },
    getPaginatedAnimalKeepers: function (query) {
        return restClient.getPaginatedCollection('/api/animal_keepers', query)
    },
    postOrganization: function (payload) {
        return restClient.post('/api/organizations', payload)
    },
    postPractitioner: function (payload) {
        return restClient.post('/api/practitioners', payload)
    },
    postPatient: function (payload) {
        return restClient.post('/api/patients', payload)
    },
    postReportEmail: function (payload) {
        return restClient.post('/api/report_emails', payload)
    },
    postAnimalKeeper: function (payload) {
        return restClient.post('/api/animal_keepers', payload)
    },
    postProbe: function (payload) {
        return restClient.post('/api/probes', payload)
    },
    postObservation: function (payload) {
        return restClient.post('/api/observations', payload)
    },
    postElmReport: function (payload) {
        return restClient.post('/api/elm_reports', payload)
    },
    postReport: function (payload) {
        return restClient.post('/api/reports', payload)
    },
    postInvoice: function (payload) {
        return restClient.post('/api/invoices', payload)
    },
    postSetIdentifiers: function (payload) {
        return restClient.post('/invoices/set_identifiers', payload)
    },
    postDownloadReceipts: function (payload) {
        return restClient.postPdf('/invoices/receipts', payload)
    },
}
