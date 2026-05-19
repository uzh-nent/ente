<template>
  <div>
    <form-field for-id="receivers" :label="$t('report_email.receivers')" :field="fields.receivers">
      <text-input id="receivers" type="text" :field="fields.receivers" v-model="entity.receivers"
                  @blur="blurField('receivers')" @update:modelValue="validateField('receivers')"/>
    </form-field>
    <form-field for-id="ccReceivers" :label="$t('report_email.cc_receivers')" :field="fields.ccReceivers">
      <text-input id="ccReceivers" type="text" :field="fields.ccReceivers" v-model="entity.ccReceivers"
                  @blur="blurField('ccReceivers')" @update:modelValue="validateField('ccReceivers')"/>
    </form-field>

    <hr/>
    <form-field for-id="subject" :label="$t('report_email.subject')" :field="fields.subject">
      <text-input id="subject" type="text" :field="fields.subject" v-model="entity.subject"
                  @blur="blurField('subject')" @update:modelValue="validateField('subject')"/>
    </form-field>

    <form-field for-id="body" :label="$t('report_email.body')" :field="fields.body">
      <text-area
          id="body" type="text" :field="fields.body" v-model="entity.body"
          @blur="blurField('body')" @update:modelValue="validateField('body')"/>
    </form-field>
  </div>
</template>

<script>
import {templatedForm, createField, requiredRule, emailsRule} from './utils/form'
import FormField from '../Library/FormLayout/FormField'
import TextInput from '../Library/FormInput/TextInput.vue'
import TextArea from '../Library/FormInput/TextArea.vue'
import DateTimeInput from '../Library/FormInput/DateTimeInput.vue'
import Radio from "../Library/FormInput/Radio.vue";
import CustomSelect from "../Library/FormInput/CustomSelect.vue";
import Checkbox from "../Library/FormInput/Checkbox.vue";
import IdentificationView from "../View/Observation/IdentificationView.vue";
import TestView from "../View/Observation/TestView.vue";
import {probeConverter} from "../../services/domain/converters";
import email from '../../../resources/report/email.json'



export default {
  emits: ['update'],
  components: {
    TestView,
    IdentificationView,
    Checkbox,
    CustomSelect,
    Radio,
    DateTimeInput,
    TextArea,
    TextInput,
    FormField
  },
  mixins: [templatedForm],
  props: {
    probe: {
      type: Object,
      required: true,
    },
    report: {
      type: Object,
      required: true,
    }
  },
  data() {
    return {
      fields: {
        receivers: createField(emailsRule, requiredRule),
        ccReceivers: createField(emailsRule),
        subject: createField(requiredRule),
        body: createField(requiredRule),
      },
      entity: {
        receivers: null,
        ccReceivers: null,
        subject: null,
        body: null,
      },
    }
  },
  mounted() {
    const receivers = [];
    if (this.probe.ordererOrg) {
      const organization = probeConverter.reconstructOrdererOrg(this.probe)
      console.log(organization)
      receivers.push(organization.email)
    }
    if (this.probe.ordererPrac) {
      const practitioner = probeConverter.reconstructOrdererPrac(this.probe)
      console.log(practitioner)
      receivers.push(practitioner.email)
    }
    this.entity.receivers = receivers.join(", ")

    this.entity.ccReceivers = this.report.copyToAddresses.map(entry => entry.email).join(", ")

    let subject = email.subject_template
    subject = subject.replace(/<report_title>/g, this.report.title)
    subject = subject.replace(/<probe_requisition_identifier>/g, this.probe.requisitionIdentifier)
    this.entity.subject = subject

    let body = email.body_template
    body = body.replace(/<report_title>/g, this.report.title)
    body = body.replace(/<probe_requisition_identifier>/g, this.probe.requisitionIdentifier)
    body = body.replace(/<probe_identifier>/g, this.probe.identifier)
    const results = this.report.results.map(entry => "- " + entry.analysis + ": " + entry.result + (entry.comment ? "*" : ""))
    let result = results.join("\n")
    if (this.report.results.some(entry => entry.comment)) {
      result += "\n* " + email.comment_notice
    }
    body = body.replace(/<report_results>/g, result)
    this.entity.body = body
  }
}
</script>
