<?xml version='1.0' encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:param name="date" />
  <xsl:output method="html" encoding="UTF-8" indent="yes" />
  <xsl:strip-space elements="*" />

  <xsl:template match="/">
    <div class="meteo--list">
      <xsl:apply-templates select="previsions/echeance" />
    </div>
  </xsl:template>

  <!-- Render HTML element -->
  <xsl:template match="echeance" mode="render">
    <div class="meteo--item">
      <div class="meteo--item_time">
        <xsl:value-of select="substring(@timestamp, 12, 5)" />
      </div>
      <!-- Température -->
      <xsl:variable name="temperature">
          <xsl:value-of select="round(temperature/level[@val='2m'] - 273.15)"/>
      </xsl:variable>
      <div class="meteo--item_temperature">
        <xsl:value-of select="$temperature" />
      </div>
      <!-- Status -->
      <div class="meteo--item_status">
        <xsl:choose>
          <xsl:when test="pluie > 0">
            <i class="fas fa-cloud-rain"></i>
          </xsl:when>
          <xsl:otherwise>
            <i class="fas fa-sun"></i>
          </xsl:otherwise>
        </xsl:choose>
      </div>
      <!-- Neige -->
      <xsl:choose>
        <xsl:when test="risque_neige = 'oui'">
          <div class="meteo--item_neige">
            <i class="fas fa-snowflake"></i>
          </div>
        </xsl:when>
      </xsl:choose>
      <!-- Humidité -->
      <div class="meteo--item_humid">
        <i class="fas fa-tint"></i>
        <xsl:value-of select="humidite/level" />
        %
      </div>
      <!-- Conseils -->
      <div class="meteo--item_advice">
        <xsl:choose>
          <xsl:when test="pluie > 0">
            <xsl:choose>
              <xsl:when test="$temperature &lt; 10">
                Uniquement si t'a pas d'autres solutions !
              </xsl:when>
              <xsl:otherwise>
                Sort la veste et prend un parapluie dans le sac.
              </xsl:otherwise>
            </xsl:choose>
          </xsl:when>
          <xsl:otherwise>
              <xsl:choose>
                <xsl:when test="$temperature &lt; 10">
                  Prend un bonnet... On sait jamais...
                </xsl:when>
                <xsl:otherwise>
                  Les conditions sont idéales
                </xsl:otherwise>
              </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
      </div>
    </div>
  </xsl:template>
  <!-- Various echances -->
  <xsl:template match="echeance">
    <xsl:variable name="datetime">
      <xsl:value-of select="substring(@timestamp, 0, 17)" />
    </xsl:variable>

    <xsl:choose>
      <xsl:when test="
        $datetime=concat($date, ' 04:00')
        or $datetime=concat($date, ' 07:00')
        or $datetime=concat($date, ' 13:00')
        or $datetime=concat($date, ' 16:00')
        or $datetime=concat($date, ' 19:00')
      ">
        <xsl:apply-templates select="." mode="render" /> 
      </xsl:when>
    </xsl:choose>
  </xsl:template>
</xsl:stylesheet>